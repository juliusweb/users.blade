@include('header')
		<!-- //header-ends -->
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page signup-page">
				<h3 class="title1">{{$settings->site_name}} users list</h3>
				
				@if(Session::has('message'))
		        <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="fa fa-info-circle"></i> {{ Session::get('message') }}
                        </div>
                    </div>
                </div>
                @endif

                @if(count($errors) > 0)
		        <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            @foreach ($errors->all() as $error)
                            <i class="fa fa-warning"></i> {{ $error }}
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

				<div class="bs-example widget-shadow table-responsive" data-example-id="hoverable-table"> 
				<span style="margin:3px;">
				        {{$users->render()}}
				    </span>
				    
				    <form style="padding:3px; float:right;" role="form" method="post" action="{{action('Controller@search')}}">
				            <a class="btn btn-default" href="{{ url('dashboard/manageusers') }}">Show all</a>
					   		<input style="padding:5px; margin-top:15px;" placeholder="Search user" type="text" name="searchItem" required>
					   		<input type="hidden" name="_token" value="{{ csrf_token() }}">
					   		<input type="hidden" name="type" value="user">
					   		<input type="submit" style="margin-top:-5px;" class="btn btn-default" value="Go">
					  </form>
					  
				<a href="#" data-toggle="modal" data-target="#sendmailModal" class="btn btn-default btn-lg" style="margin:10px;">Message all</a>
				
					<table class="table table-hover"> 
						<thead> 
							<tr> 
								<th>ID</th> 
								<th>Balance</th> 
								<th>Full name</th> 
								<th>Email</th> 
								<th>Inv. plan</th>
								<th>Status</th>
								<th>Date registered</th> 
								<th>Action</th> 
							</tr> 
						</thead> 
						<tbody> 
							@foreach($users as $list)
							<tr> 
								<th scope="row">{{$list->id}}</th>
								 <td>${{$list->account_bal}}</td> 
								 <td>{{$list->name}}</td> 
								 <td>{{$list->email}}</td> 
								 @if(isset($list->dplan->name)) 
								 <td>{{$list->dplan->name}}</td>
								 @else
								 <td>NULL</td>
								 @endif 
								 <td>{{$list->status}}</td> 
								 <td>{{$list->created_at}}</td> 
								 <td>
								 @if($list->status==NULL || $list->status=='blocked')
								 <a class="btn btn-default" href="{{ url('dashboard/unblock') }}/{{$list->id}}">Unblock</a> 
								 @else
								 <a class="btn btn-default" href="{{ url('dashboard/ublock') }}/{{$list->id}}">Block</a>
								 @endif
								 <a href="#"  data-toggle="modal" data-target="#topupModal{{$list->id}}" class="btn btn-default">Top up</a>
								 <a href="#" data-toggle="modal" data-target="#resetpswdModal{{$list->id}}"  class="btn btn-default">Reset Password</a>
								 <a href="#" data-toggle="modal" data-target="#eachsendmailModal{{$list->id}}" class="btn btn-default btn-default">Message</a>
								 <a href="#" data-toggle="modal" data-target="#clearacctModal{{$list->id}}" class="btn btn-default">Clear Account</a>
								 <a href="{{ url('dashboard/deluser') }}/{{$list->id}}" class="btn btn-default">Delete</a>
								 </td> 
							</tr> 

								<!-- Deposit for a plan Modal -->
								<div id="topupModal{{$list->id}}" class="modal fade" role="dialog">
							<div class="modal-dialog">

								<!-- Modal content-->
								<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title" style="text-align:center;">Top up user account.</strong></h4>
								</div>
								<div class="modal-body">
										<form style="padding:3px;" role="form" method="post" action="{{action('SomeController@topup')}}">
											<input style="padding:5px;" class="form-control" value="{{$list->name}}" type="text" disabled><br/>
											<input style="padding:5px;" class="form-control" placeholder="Enter amount to top up" type="text" name="amount" required><br/>
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<input type="hidden" name="user_id" value="{{$list->id}}">
											<input type="submit" class="btn btn-default" value="Credit account">
									</form>
								</div>
								</div>
							</div>
							</div>
							<!-- /deposit for a plan Modal -->

							<!-- Reset user password Modal -->
							<div id="resetpswdModal{{$list->id}}" class="modal fade" role="dialog">
							<div class="modal-dialog">

								<!-- Modal content-->
								<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title" style="text-align:center;">You are reseting password for {{$list->name}}.</strong></h4>
								</div>
								<div class="modal-body">
									<p>Default password:</p>
									<h3>user01236</h3><br>
									<a class="btn btn-default" href="{{ url('dashboard/resetpswd') }}/{{$list->id}}">Proceed</a>
								</div>
								</div>
							</div>
							</div>
							<!-- /Reset user password Modal -->

							<!-- Clear account Modal -->
							<div id="clearacctModal{{$list->id}}" class="modal fade" role="dialog">
							<div class="modal-dialog">

								<!-- Modal content-->
								<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title" style="text-align:center;">You are clearing account for {{$list->name}} to $0.00</strong></h4>
								</div>
								<div class="modal-body">
									<a class="btn btn-default" href="{{ url('dashboard/clearacct') }}/{{$list->id}}">Proceed</a>
								</div>
								</div>
							</div>
							</div>
							<!-- /Clear account Modal -->
							
				<!-- send EACH users email -->
			<div id="eachsendmailModal{{$list->id}}" class="modal fade" role="dialog">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title" style="text-align:center;">Send Message to {{$list->email}} <strong>({{$list->name}})</strong></h4>
			      </div>
			      <div class="modal-body">
                        <form style="padding:3px;" role="form" method="post" action="{{action('UsersController@sendmail')}}">
                            
                            <input style="padding:5px;" class="form-control" value="{{$list->email}}" type="text" disabled><br/>
					   		
					   		<textarea class="form-control" name="message" row="3" required=""></textarea><br/>
                               
					   		<input type="hidden" name="_token" value="{{ csrf_token() }}">
					   		<input type="submit" class="btn btn-default" value="Send">
					   </form>
			      </div>
			    </div>
			  </div>
			</div>
			<!-- /send EACH users email Modal -->
							
							@endforeach
							
						</tbody> 
					</table>
				</div>
			</div>
		</div>
		@include('modals')
		@include('footer')