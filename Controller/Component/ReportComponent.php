<?php
App::uses('Component', 'Controller');
class ReportComponent extends Component{
	
    /**
     * To Conver CSV file to Array
     *
     * Date Created  : 22 November 2010
     * Last Modified : 28 November 2010
     *
     * @param string $_sFile path of file
     * @return array
     *
     * @access private
     * @author Rijal Asep Nugroho
     * @since mtemplate 1.0 version
     */
    function __csvToArray($_sFile){
        $aReturn=array();
        $row=1;
        if (($handlefile = fopen($_sFile, "r")) !== FALSE) {
            while (($data = fgetcsv($handlefile, 1000, ";")) !== FALSE) {
                $aReturn[$row]=$data;
                $row++;
            }
            fclose($handlefile);
        }
        return $aReturn;
    }

    /**
     * To Convert Array to CSV file, if array need a Model
     * (standard retrive data with find function)
     *
     * Date Created  : 22 November 2010
     * Last Modified : 28 November 2010
     *
     * @param string $_sFileName file name of output file
     * @param array $_aHeader
     * @param array $_aData
     * @param string $_sModel model data
     * @return string file csv
     *
     * @access private
     * @author Rijal Asep Nugroho
     * @since mtemplate 1.0 version
     */
    function __toCsv($_sFileName, $_aHeader, $_aData, $_sModel, $separator=','){
        // Send Header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header ("content-type: text/csv");
        header("Content-Disposition: attachment;filename=$_sFileName.csv");
        header("Content-Transfer-Encoding: binary ");

        $row=1;
        $aCsv=array();
        foreach($_aData as $v){
            $aTemp=array();
            foreach ($_aHeader as $j=>$k) $aTemp[]='"'.$v[$_sModel][$k].'"';
            $aCsv[]=implode($separator,$aTemp);
            $row++;
        }

        return implode("\r\n",$aCsv);
    }

    /**
     * To COnvert array to CVS file without model
     * (custome retrive data with query function)
     *
     * Date Created  : 22 November 2010
     * Last Modified : 28 November 2010
     *
     * @param string $_sFileName file name of output file
     * @param array $_aHeader
     * @param array $_aData
     * @return string file csv
     *
     * @access private
     * @author Rijal Asep Nugroho
     * @since mtemplate 1.0 version
     */
    function __toCsv2($_sFileName, $_aHeader, $_aData){
        // Send Header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header ("content-type: text/csv");
        header("Content-Disposition: attachment;filename=$_sFileName.csv");
        header("Content-Transfer-Encoding: binary ");

        $row=1;
        $aCsv=array();
        foreach($_aData as $v){
            $aTemp=array();
            if (is_array($v)) foreach ($v as $j) $aTemp[]='"'.$j.'"';
            $aCsv[]=implode(',',$aTemp);
            $row++;
        }

        return implode("\r\n",$aCsv);
    }

    /**
     * To convert array to excel with model
     * (standard retrive data with find function)
     *
     * Date Created  : 22 November 2010
     * Last Modified : 28 November 2010
     *
     * @param string $_sFileName filename of output file
     * @param array $_aHeader
     * @param array $_aData
     * @param string $_sModel model data
     * @param string $_sNumber='NO' number header, default NO, you can change with nomor or etc
     * @param boolean $_iNumber=true true if you need number coloumn.
     * @return object XlsUtilities
     *
     * @access private
     * @author Rijal Asep Nugroho
     * @since mtemplate 1.0 version
     */
    function __toExcel($_sFileName, $_aHeader, $_aData, $_sModel, $_sNumber='NO', $_iNumber=1, $_sTitle=null){
        // Send Header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=$_sFileName.xls");
        header("Content-Transfer-Encoding: binary ");
		
        // XLS Data Cell
        $row=0;
		$exel=$this->XlsUtilities->_xlsBOF();
		if (!is_null($_sTitle)) {
			$exel.=$this->XlsUtilities->_xlsWriteLabel($row,0,$_sTitle);
			$row++;
		}
		
        if ($_iNumber) {
			$exel.=$this->XlsUtilities->_xlsWriteLabel($row,0,$_sNumber);
		}
		
		$i=1;
        foreach ($_aHeader as $j=>$v) {
			if (is_array($v) and isset($v['label']) and !empty($v['label'])) $v=$v['label'];
            else {
				$v=is_array($v)?$j:$v;
				$h=explode('.',$v);
				if (isset($h[2])) $v=$h[2];
				elseif (isset($h[1])) $v=$h[1];
			}
			$exel.=$this->XlsUtilities->_xlsWriteLabel($row,$i,$v);
            $i++;
        }
		
		$row++;
		$number=1;
        foreach($_aData as $v){
            if ($_iNumber) $exel.=$this->XlsUtilities->_xlsWriteNumber($row,0,"$number");
            $i=$_iNumber;

            foreach ($_aHeader as $j=>$k) {
				$k=is_array($k)?$j:$k;
				$h=explode('.',$k);
				if (isset($h[2])) $exel.=$this->XlsUtilities->_xlsWriteLabel($row,$i,"{$v[$h[0]][$h[1]][$h[2]]}");
				elseif (isset($h[1])) $exel.=$this->XlsUtilities->_xlsWriteLabel($row,$i,"{$v[$h[0]][$h[1]]}");
                else $exel.=$this->XlsUtilities->_xlsWriteLabel($row,$i,"{$v[$_sModel][$k]}");
                $i++;
            }
            $row++;
			$number++;
        }
		
        $exel.=$this->XlsUtilities->_xlsEOF();
        return $exel;
    }

    /**
     * To convert array to excel without model
     * (custome retrive data with query function)
     *
     * Date Created  : 22 November 2010
     * Last Modified : 28 November 2010
     *
     * @param string $_sFileName filename of output file
     * @param array $_aHeader
     * @param array $_aData
     * @param string $_sNumber='NO' number header, default NO, you can change with nomor or etc
     * @param boolean $_iNumber=true set true if you need number coloumn.
     * @return object XlsUtilities
     *
     * @access private
     * @author Rijal Asep Nugroho
     * @since mtemplate 1.0 version
     */
    function __toExcel2($_sFileName, $_aHeader, $_aData, $_sNumber='NO', $_iNumber=1){
        // Send Header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=$_sFileName.xls");
        header("Content-Transfer-Encoding: binary ");

        // XLS Data Cell
        $exel=$this->XlsUtilities->_xlsBOF();
        if ($_iNumber) $exel.=$this->XlsUtilities->_xlsWriteLabel(0,0,$_sNumber);

        $i=$_iNumber;
        foreach ($_aHeader as $v) {
            $exel.=$this->XlsUtilities->_xlsWriteLabel(0,$i,$v);
            $i++;
        }

        $row=1;
        foreach($_aData as $v){
            if ($_iNumber) $exel.=$this->XlsUtilities->_xlsWriteNumber($row,0,"$row");
            $i=$_iNumber;

            if (is_array($v)) {
                foreach ($v as $j) {
                    $exel.=$this->XlsUtilities->_xlsWriteLabel($row,$i,"{$j}");
                    $i++;
                }
            }
            $row++;
        }

        $exel.=$this->XlsUtilities->_xlsEOF();
        return $exel;
    }

    /**
     * To Convert array to pdf
     *
     * Date Created  : 22 November 2010
     * Last Modified : 28 November 2010
     *
     * @param array $data
     *
     * @access private
     * @author Rijal Asep Nugroho
     * @since mtemplate 1.0 version
     */
    function __toPDF($data){
        $font=(isset($data['font']))?$data['font']:'Arial';
        $fontsize=(isset($data['fontsize']))?$data['fontsize']:11;
        $type=(isset($data['type']))?$data['type']:'fancy';

        require_once(VENDORS.'fpdf'.DS.'pdf.php');
        $pdf=new PDF();
        $pdf->SetFont($font,'',$fontsize);
        $pdf->AddPage();
        if (isset($data['type']) and $data['type']=='basic') {
            $pdf->BasicTable($data['header'],$data['data']);
        }
        elseif (isset($data['type']) and $data['type']=='base'){
            $pdf->ImprovedTable($data['header'],$data['data']);
        }
        else $pdf->FancyTable($data['header'],$data['data']);
        $pdf->Output();
    }
}
