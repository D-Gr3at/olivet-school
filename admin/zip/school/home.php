<?php
require_once('libs/dbfunctions.php');
if(!isset($_SESSION['username_sess']))
{
    header('location: logout.php');
}

require_once('class/menu.php');
$menu = new Menu();
$menu_list = $menu->generateMenu($_SESSION['role_id_sess']);
$menu_list = $menu_list['data'];
// var_dump($_SESSION);
$dbobject = new dbobject();
?>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from appstack.bootlab.io/dashboard-default.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 26 Jul 2019 15:56:51 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
	<meta name="author" content="Bootlab">

	<title>The Lord's Chosen Charismatic Revival Church</title>

    <link rel="preconnect" href="http://fonts.gstatic.com/" crossorigin>
    <link rel="icon" href="img/icon.png" sizes="32x32" />
    <link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />

	<!-- PICK ONE OF THE STYLES BELOW -->
	<!-- <link href="css/classic.css" rel="stylesheet"> -->
	<!-- <link href="css/corporate.css" rel="stylesheet"> -->
	<!-- <link href="css/modern.css" rel="stylesheet"> -->

	<!-- BEGIN SETTINGS -->
	<!-- You can remove this after picking a style -->
	<style>
		body {
			opacity: 0;
		}
	</style>
	<script src="js/settings.js"></script>
	<!-- END SETTINGS -->
<!-- Global site tag (gtag.js) - Google Analytics -->

    <script src="js/app.js"></script>
    <script src="js/jquery.blockUI.js"></script>
	<script src="js/parsely.js"></script>
	
	<script src="js/sweet_alerts.js"></script>
	<script src="js/main.js"></script>
	<script src="codebase/dhtmlxcalendar.js"></script>
</head>

<body>
	<div class="wrapper">
		<nav class="sidebar">
			<div class="sidebar-content ">
				<a class="sidebar-brand" target="_blank" href="https://www.tlccrm.org/">
         <img src="img/logo.png" style="max-width: 90%" alt="The Lord's Chosen Logo">
          <!-- <span class="align-middle"></span> -->
        </a>

				<ul class="sidebar-nav">
					<li class="sidebar-item">
                        <button style="background:#fff" class="btn btn-outline-success btn-block d-inline-block d-sm-none" disabled="">
                            <?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?>
                        </button>
                        <a style="margin-top:0" href="javascript:window.location='logout.php'" class="d-inline-block d-sm-none btn btn-danger btn-block">
                            Logout
                        </a>
						<a href="home.php" data-toggle="" class="sidebar-link collapsed">
              				 <span class="align-middle">Dashboard</span>
            			</a>
						
                        <?php
                        foreach($menu_list as $row)
                        {
                        ?>
                            <a href="#k<?php echo $row['menu_id']; ?>" data-toggle="collapse" class="sidebar-link collapsed">
                                <i class="align-middle" data-feather="sliders"></i> <span class="align-middle"><?php echo $row['menu_name']; ?></span>
                            </a>
                            <?php
                                if($row['has_sub_menu'] == true)
                                {
                                    echo '<ul id="k'.$row['menu_id'].'"  class="sidebar-dropdown list-unstyled collapse">';
                                    foreach($row['sub_menu'] as $row2)
                                    {
                            ?>
                                        <li class="sidebar-item"><a class="sidebar-link" href="javascript:getpage('<?php echo $row2['menu_url']; ?>','page')"><?php echo $row2['name']; ?></a>
                                        </li>
                            <?php
                                    }
                                    echo '</ul>';
                                }
                            ?>
                        <?php
                        }
                        ?>
                    </li>
                    
					
				</ul>

				<div class="sidebar-bottom d-none d-lg-block">
					<div class="media">
						<img class="rounded-circle mr-3" src="<?php echo $_SESSION['photo_path_sess']; ?>" alt="<?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?>" width="40" height="40">
						<div class="media-body">
							<h5 class="mb-1"><?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?></h5>
							
                            <div>
                                <button class="btn btn-danger btn-block" onclick="window.location='logout.php'">Logout</button>
                            </div>
						</div>
					</div>
				</div>

			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light bg-white">
				<a class="sidebar-toggle d-flex mr-2">
                    <i class="hamburger align-self-center"></i>
                </a>
                <a href="javascript:void(0)" style="text-decoration:none" class="d-flex mr-2">
                    Your Role: &nbsp; 
                    <span style="font-weight:bold; color:#000">
                        <?php 
                            echo $_SESSION['role_id_name']; 
                            echo ($_SESSION[role_id_sess] != '001')?" from ".$dbobject->getitemlabel('towns','id',$_SESSION['town_id_sess'],'town_name'):"";
                        ?>
                        
                    </span>
                </a>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ml-auto">
						
					
						<li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings align-middle"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        </a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-toggle="dropdown">
                <img src="<?php echo $_SESSION['photo_path_sess'] ?>" class="avatar img-fluid rounded-circle mr-1" alt="<?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?>" /> <span class="text-dark"><?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?></span>
              </a>
							<div class="dropdown-menu dropdown-menu-right">
								<a class="dropdown-item" href="javascript:getpage('profile.php','page')"><i class="align-middle mr-1" data-feather="user"></i> Profile</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="logout.php">Sign out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content" id="page">
				<div class="container-fluid p-0">

					<div class="row">
						<div class="col-12 col-sm-6 col-xl d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<i class="feather-lg text-primary" data-feather="shopping-cart"></i>
										</div>
										<div class="media-body">
											<h3 class="mb-2">2.562</h3>
											<div class="mb-0">Tithe</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-sm-6 col-xl d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<i class="feather-lg text-warning" data-feather="activity"></i>
										</div>
										<div class="media-body">
											<h3 class="mb-2">17.212</h3>
											<div class="mb-0">Offering</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-sm-6 col-xl d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<i class="feather-lg text-success" data-feather="dollar-sign"></i>
										</div>
										<div class="media-body">
											<h3 class="mb-2">$ 24.300</h3>
											<div class="mb-0">Projects</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-sm-6 col-xl d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<i class="feather-lg text-danger" data-feather="shopping-bag"></i>
										</div>
										<div class="media-body">
											<h3 class="mb-2">43</h3>
											<div class="mb-0">Donations</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-sm-6 col-xl d-none d-xxl-flex">
							<div class="card flex-fill">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<i class="feather-lg text-info" data-feather="dollar-sign"></i>
										</div>
										<div class="media-body">
											<h3 class="mb-2">$ 18.700</h3>
											<div class="mb-0">Evangelism</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12 col-lg-8 d-flex">
							<div class="card flex-fill w-100">
								<div class="card-header">
									<span class="badge badge-primary float-right">Monthly</span>
									<h5 class="card-title mb-0">Total Revenue (September)</h5>
								</div>
								<div class="card-body">
									<div class="chart chart-lg">
										<canvas id="chartjs-dashboard-line"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-4 d-flex">
                        <div class="card flex-fill w-100">
								<div class="card-header">
									<div class="card-actions float-right">
										<div class="dropdown show">
											<a href="#" data-toggle="dropdown" data-display="static">
                                                <i class="align-middle" data-feather="more-horizontal"></i>
                                            </a>

											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="#">Action</a>
												<a class="dropdown-item" href="#">Another action</a>
												<a class="dropdown-item" href="#">Something else here</a>
											</div>
										</div>
									</div>
									<h5 class="card-title mb-0">Pie Distribution</h5>
								</div>
								<div class="card-body d-flex">
									<div class="align-self-center w-100">
										<div class="py-3">
											<div class="chart chart-xs">
												<canvas id="chartjs-dashboard-pie"></canvas>
											</div>
										</div>

										<table class="table mb-0">
											<thead>
												<tr>
													<th>Source</th>
													<th class="text-right">Revenue</th>
													<th class="text-right">Value</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><i class="fas fa-square-full text-primary"></i> Tithe</td>
													<td class="text-right">&#8358; 2602</td>
													<td class="text-right text-success">+43%</td>
												</tr>
												<tr>
													<td><i class="fas fa-square-full text-warning"></i> Offering</td>
													<td class="text-right">&#8358; 1253</td>
													<td class="text-right text-success">+13%</td>
												</tr>
												<tr>
													<td><i class="fas fa-square-full text-danger"></i> Projects</td>
													<td class="text-right">&#8358; 541</td>
													<td class="text-right text-success">+24%</td>
												</tr>
												<tr>
													<td><i class="fas fa-square-full text-dark"></i> Donations</td>
													<td class="text-right">&#8358; 1465</td>
													<td class="text-right text-success">+11%</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
                    <div class="col-12 col-lg-6 col-xl-12 d-flex">
							<div class="card flex-fill">
								<div class="card-header">
									<div class="card-actions float-right">
										<div class="dropdown">
											<a href="#" data-toggle="dropdown" data-display="static" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
            </a>

											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="#">Action</a>
												<a class="dropdown-item" href="#">Another action</a>
												<a class="dropdown-item" href="#">Something else here</a>
											</div>
										</div>
									</div>
									<h5 class="card-title mb-0">Operations Report</h5>
								</div>
								<div id="datatables-dashboard-projects_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="datatables-dashboard-projects" class="table table-striped my-0 dataTable no-footer" role="grid" aria-describedby="datatables-dashboard-projects_info">
									<thead>
										<tr role="row"><th class="sorting" tabindex="0" aria-controls="datatables-dashboard-projects" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Name</th><th class="d-none d-xl-table-cell sorting" tabindex="0" aria-controls="datatables-dashboard-projects" rowspan="1" colspan="1" aria-label="Start Date: activate to sort column ascending">Start Date</th><th class="d-none d-xl-table-cell sorting" tabindex="0" aria-controls="datatables-dashboard-projects" rowspan="1" colspan="1" aria-label="End Date: activate to sort column ascending">End Date</th><th class="sorting" tabindex="0" aria-controls="datatables-dashboard-projects" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th><th class="d-none d-md-table-cell sorting_desc" tabindex="0" aria-controls="datatables-dashboard-projects" rowspan="1" colspan="1" aria-label="Assignee: activate to sort column ascending" aria-sort="descending">Assignee</th></tr>
									</thead>
									<tbody>
										
										
										
										
										
										
										
										
										
									<tr role="row" class="odd">
											<td class="">Project Hades</td>
											<td class="d-none d-xl-table-cell">01/01/2018</td>
											<td class="d-none d-xl-table-cell">31/06/2018</td>
											<td><span class="badge badge-success">Done</span></td>
											<td class="d-none d-md-table-cell sorting_1">Stacie Hall</td>
										</tr><tr role="row" class="even">
											<td class="">Project X</td>
											<td class="d-none d-xl-table-cell">01/01/2018</td>
											<td class="d-none d-xl-table-cell">31/06/2018</td>
											<td><span class="badge badge-success">Done</span></td>
											<td class="d-none d-md-table-cell sorting_1">Stacie Hall</td>
										</tr><tr role="row" class="odd">
											<td class="">Project Zircon</td>
											<td class="d-none d-xl-table-cell">01/01/2018</td>
											<td class="d-none d-xl-table-cell">31/06/2018</td>
											<td><span class="badge badge-danger">Cancelled</span></td>
											<td class="d-none d-md-table-cell sorting_1">Stacie Hall</td>
										</tr><tr role="row" class="even">
											<td class="">Project Apollo</td>
											<td class="d-none d-xl-table-cell">01/01/2018</td>
											<td class="d-none d-xl-table-cell">31/06/2018</td>
											<td><span class="badge badge-success">Done</span></td>
											<td class="d-none d-md-table-cell sorting_1">Carl Jenkins</td>
										</tr><tr role="row" class="odd">
											<td class="">Project Nitro</td>
											<td class="d-none d-xl-table-cell">01/01/2018</td>
											<td class="d-none d-xl-table-cell">31/06/2018</td>
											<td><span class="badge badge-warning">In progress</span></td>
											<td class="d-none d-md-table-cell sorting_1">Carl Jenkins</td>
										</tr><tr role="row" class="even">
											<td class="">Project Fireball</td>
											<td class="d-none d-xl-table-cell">01/01/2018</td>
											<td class="d-none d-xl-table-cell">31/06/2018</td>
											<td><span class="badge badge-danger">Cancelled</span></td>
											<td class="d-none d-md-table-cell sorting_1">Bertha Martin</td>
										</tr></tbody>
								</table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="datatables-dashboard-projects_info" role="status" aria-live="polite">Showing 1 to 6 of 9 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="datatables-dashboard-projects_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="datatables-dashboard-projects_previous"><a href="#" aria-controls="datatables-dashboard-projects" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="datatables-dashboard-projects" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="datatables-dashboard-projects" data-dt-idx="2" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item next" id="datatables-dashboard-projects_next"><a href="#" aria-controls="datatables-dashboard-projects" data-dt-idx="3" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>
							</div>
						</div>
					
						
					</div>


				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-left">
							<ul class="list-inline">
								
								<li class="list-inline-item">
									<a class="text-muted" href="#">Help Center</a>
								</li>
								
							</ul>
						</div>
						<div class="col-6 text-right">
							<p class="mb-0">
								&copy; <?php echo date('Y'); ?> - <a target="_blank" href="https://www.tlccrm.org/" class="text-muted">The Lord's Chosen Charismatic Revival Church</a>
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
	<style>
        .dhtmlxcalendar_material 
        {
            z-index: 99999 !important;
        }
    </style>
<link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
<script src="codebase/dhtmlxcalendar.js"></script>
	
	<script>
		$(function() {
			// Bar chart
			new Chart(document.getElementById("chartjs-dashboard-bar"), {
				type: "bar",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Last year",
						backgroundColor: window.theme.primary,
						borderColor: window.theme.primary,
						hoverBackgroundColor: window.theme.primary,
						hoverBorderColor: window.theme.primary,
						data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79]
					}, {
						label: "This year",
						backgroundColor: "#E8EAED",
						borderColor: "#E8EAED",
						hoverBackgroundColor: "#E8EAED",
						hoverBorderColor: "#E8EAED",
						data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51, 68]
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								display: false
							},
							stacked: false,
							ticks: {
								stepSize: 20
							}
						}],
						xAxes: [{
							barPercentage: .75,
							categoryPercentage: .5,
							stacked: false,
							gridLines: {
								color: "transparent"
							}
						}]
					}
				}
			});
		});
	</script>
	<script>
		$(function() {
			$("#datetimepicker-dashboard").datetimepicker({
				inline: true,
				sideBySide: false,
				format: "L"
			});
		});
	</script>
	<script>
		$(function() {
			// Line chart
			new Chart(document.getElementById("chartjs-dashboard-line"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Sales ($)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: [2015, 1465, 1487, 1796, 1387, 2123, 2866, 2548, 3902, 4938, 3917, 4927]
					}, {
						label: "Orders",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.tertiary,
						borderDash: [4, 4],
						data: [928, 734, 626, 893, 921, 1202, 1396, 1232, 1524, 2102, 1506, 1887]
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					tooltips: {
						intersect: false
					},
					hover: {
						intersect: true
					},
					plugins: {
						filler: {
							propagate: false
						}
					},
					scales: {
						xAxes: [{
							reverse: true,
							gridLines: {
								color: "rgba(0,0,0,0.05)"
							}
						}],
						yAxes: [{
							ticks: {
								stepSize: 500
							},
							display: true,
							borderDash: [5, 5],
							gridLines: {
								color: "rgba(0,0,0,0)",
								fontColor: "#fff"
							}
						}]
					}
				}
			});
		});
	</script>
	<script>
		$(function() {
			// Pie chart
			new Chart(document.getElementById("chartjs-dashboard-pie"), {
				type: "pie",
				data: {
					labels: ["Direct", "Affiliate", "E-mail", "Other"],
					datasets: [{
						data: [2602, 1253, 541, 1465],
						backgroundColor: [
							window.theme.primary,
							window.theme.warning,
							window.theme.danger,
							"#E8EAED"
						],
						borderColor: "transparent"
					}]
				},
				options: {
					responsive: !window.MSInputMethodContext,
					maintainAspectRatio: false,
					legend: {
						display: false
					}
				}
			});
		});
	</script>
	<script>
		$(function() {
			$("#datatables-dashboard-projects").DataTable({
				pageLength: 6,
				lengthChange: false,
				bFilter: false,
				autoWidth: false
			});
		});
	</script>

<div class="modal fade" id="defaultModalPrimary" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal_div">
            <div class="modal-header">
                <h5 class="modal-title">Default modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body m-3">
                <p class="mb-0">Use Bootstrap’s JavaScript modal plugin to add dialogs to your site for lightboxes, user notifications, or completely custom content.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sizedModallg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="modal_div2">
            <div class="modal-header">
                <h5 class="modal-title">Default modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body m-3">
                <p class="mb-0">Use Bootstrap’s JavaScript modal plugin to add dialogs to your site for lightboxes, user notifications, or completely custom content.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
</body>


<!-- Mirrored from appstack.bootlab.io/dashboard-default.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 26 Jul 2019 15:57:08 GMT -->
</html>