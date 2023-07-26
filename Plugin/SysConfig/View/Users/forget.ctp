<?php $this->Session->flash('forget'); 
echo $this->Form->create('UserAccount', array('url' => '/sys_config/users/forget', 'class'=>'forgetPassword'));
?>

<div class="container">    
	<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
		<div class="panel panel-default" >
			<div class="panel-heading">
				<div class="panel-title">Forget Password</div>
			</div>     

			<div style="padding-top:30px" class="panel-body" >

				<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
					
				<div class="form-horizontal" >
							
					<div style="margin-bottom: 15px" class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
								<?php echo $this->Form->input('emaill Address',array('label'=>'','class'=>'form-control','placeholder'=>'email address'));?>                                        
							</div>
						
					
						
						<div style="margin-top:10px" class="form-group">
							<!-- Button -->
							
							<div class="col-sm-12 controls">
							  <input type="submit" class="btn btn-primary" value="Reset Password" />
							  
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12 control">
								<div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
								
								Don't have an account! <?php echo $this->Html->link('Sign Up Here.', '/signup');?> </a>
								</div>
							</div>
						</div>    
						

					</div>      


				</div> 	
			</div> 
										
		</div>  
	</div>
        
</div>

</form>

