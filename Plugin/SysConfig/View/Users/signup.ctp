<?php $this->Session->flash('signup'); 
echo $this->Form->create('UserAccount', array('url' => '/sys_config/users/signup', 'class'=>'signup'));
?>

<div class="container">    
	<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
		<div class="panel panel-default" >
			<div class="panel-heading">
				<div class="panel-title">Sign Up</div>
			</div>     

			<div style="padding-top:30px" class="panel-body" >

				<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
					
					<div class="form-horizontal" >
								
						<div style="margin-bottom: 15px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<?php echo $this->Form->input('username',array('label'=>'','class'=>'form-control','placeholder'=>'Full Name'));?>                                        
						</div>
						
						<div style="margin-bottom: 15px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
							<?php echo $this->Form->input('emaill Address',array('label'=>'','class'=>'form-control','placeholder'=>'Email Address'));?>                                        
						</div>
						
						<div style="margin-bottom: 15px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-plus"></i></span>
							<?php echo $this->Form->input('username',array('label'=>'','class'=>'form-control','placeholder'=>'User Name'));?>                                        
						</div>
						
						<div style="margin-bottom: 15px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<?php echo $this->Form->input('password1', array('type'=>'password','label'=>'','class'=>'form-control','placeholder'=>'Password'));?>
						</div>
						
						
						<div style="margin-bottom: 15px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<?php echo $this->Form->input('password1', array('type'=>'password','label'=>'','class'=>'form-control','placeholder'=>'Re-Confirm Password'));?>
						</div>
						
							
						
							
						<div style="margin-top:10px" class="form-group">
							<!-- Button -->
							
							<div class="col-sm-12 controls">
							  <input type="submit" class="btn btn-primary" value="Submit" />
							  
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12 control">
								<div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
								
								Already have account, <?php echo $this->Html->link('Sign in here.', '/login');?> </a>
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

