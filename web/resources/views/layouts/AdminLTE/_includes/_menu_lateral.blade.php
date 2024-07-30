<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header" style="color:#fff;"> MAIN MENU <i class="fa fa-level-down"></i></li>  
			<li class="
						{{ Request::segment(1) === null ? 'active' : null }}
						{{ Request::segment(1) === 'home' ? 'active' : null }}
					  ">
				<a href="{{ route('home') }}" title="Dashboard"><i class="fa fa-dashboard"></i> <span> Dashboard</span></a>
			</li>

			{{-- {{ Request::segment(1) }} --}}
			<li class="{{ Request::segment(1) === 'hotspot' && Request::segment(2) === 'user' ? 'active' : null }}">
				<a href="{{ route('hotspot.user.list') }}" title="User">
					<i class="fa fa-users"></i> <span> User</span>
				</a>
			</li>

			{{-- @endif --}}

			{{-- {{ Request::segment(1) }} --}}
			<li class="{{ Request::segment(1) === 'logs' ? 'active' : null }}">
				<a href="{{ route('logs') }}" title="Logs">
					<i class="fa fa-file-text-o"></i> <span> Logs</span>
				</a>
			</li>
			{{-- @endif --}}

			{{-- {{ Request::segment(1) }} --}}
			<li class="{{ Request::segment(1) === 'scheduler' ? 'active' : null }}">
				<a href="{{ route('scheduler.list') }}" title="Scheduler">
					<i class="fa fa-clock-o"></i> <span> Scheduler</span>
				</a>
			</li>
			{{-- @endif --}}

			{{-- {{ Request::segment(1) }} --}}
			<li class="{{ Request::segment(1) === 'traffic' ? 'active' : null }}">
				<a href="{{ route('traffic.monitor.detail') }}" title="Monitoring Traffic">
					<i class="fa fa-line-chart"></i> <span> Monitoring Traffic</span>
				</a>
			</li>
			{{-- @endif --}}

			@if(Request::segment(1) === 'profile')

			<li class="{{ Request::segment(1) === 'profile' ? 'active' : null }}">
				<a href="{{ route('profile') }}" title="Profile"><i class="fa fa-user"></i> <span> PROFILE</span></a>
			</li>

			@endif
			<li class="treeview 
				{{ Request::segment(1) === 'config' ? 'active menu-open' : null }}
				{{ Request::segment(1) === 'user' ? 'active menu-open' : null }}
				{{ Request::segment(1) === 'role' ? 'active menu-open' : null }}
				">
				<a href="#">
					<i class="fa fa-gear"></i>
					<span>Administration</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu">
					@if (Auth::user()->can('root-dev', ''))
						<li class="{{ Request::segment(1) === 'config' && Request::segment(2) === null ? 'active' : null }}">
							<a href="{{ route('config') }}" title="App Config">
								<i class="fa fa-gear"></i> <span> Settings App</span>
							</a>
						</li>
					@endif					
					<li class="
						{{ Request::segment(1) === 'user' ? 'active' : null }}
						{{ Request::segment(1) === 'role' ? 'active' : null }}
						">
						<a href="{{ route('user') }}" title="Users">
							<i class="fa fa-user"></i> <span> Users</span>
						</a>
					</li>
				</ul>
			</li>      
		</ul>
	</section>
</aside>