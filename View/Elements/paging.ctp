<?php
if (!isset($paginator->params['paging'])) {
  return;
}
if (!isset($model) || $paginator->params['paging'][$model]['pageCount'] < 2) {
  return;
}
if (!isset($this->Paginator->options)) {
  $this->Paginator->options = array();
}
$this->Paginator->options['url']=$this->passedArgs;
$this->Paginator->options['model'] = $model;
$this->Paginator->options['url']['model'] = $model;
$paginator->__defaultModel = $model;

if (isset($trKaryawan)) {
  $this->Paginator->options['url'][] = $trKaryawan['TrKaryawan']['id'];
} elseif (isset($branch)) {
  $this->Paginator->options['url'][] = $branch['Branch']['id'];
}

?>
<div class="paging">
  <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
  | <?php echo $this->Paginator->numbers();?>
  |	<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
</div>