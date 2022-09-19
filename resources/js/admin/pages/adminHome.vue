<template>
	<div>
		<div class="content">
			<div class="container-fluid mt-3">
				<div class="row">
					<div class="col-12 col-md-3">
						<div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_one">
							<div class="_1adminOverveiw_card_left">
								<p class="_1adminOverveiw_card_left_num">3</p>

								<p class="_1adminOverveiw_card_left_title">Today's News</p>
							</div>
							<div class="_1adminOverveiw_card_right">
								<Icon type="md-aperture" />
							</div>
						</div>
					</div>

					<div class="col-12 col-md-3">
						<div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
							<div class="_1adminOverveiw_card_left">
								<p class="_1adminOverveiw_card_left_num">40</p>

								<p class="_1adminOverveiw_card_left_title">Total News</p>
							</div>
							<div class="_1adminOverveiw_card_right">
								<Icon type="md-chatboxes" />
							</div>
						</div>
					</div>

					<div class="col-12 col-md-3">
						<div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
							<div class="_1adminOverveiw_card_left">
								<p class="_1adminOverveiw_card_left_num">29</p>

								<p class="_1adminOverveiw_card_left_title">Features News</p>
							</div>
							<div class="_1adminOverveiw_card_right">
								<Icon type="md-compass" />
							</div>
						</div>
					</div>

					<div class="col-12 col-md-3">
						<div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
							<div class="_1adminOverveiw_card_left">
								<p class="_1adminOverveiw_card_left_num">21</p>

								<p class="_1adminOverveiw_card_left_title">Card News</p>
							</div>
							<div class="_1adminOverveiw_card_right">
								<Icon type="md-ribbon" />
							</div>
						</div>
					</div>
				</div>	
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<h1 style="text-align: center;color: #000; margin-bottom: 15px">User Guide</h1>

					<!-- <div class="_overflow _table_div">
						<table class="_table">
								
							<tr>
								<th>Date</th>
								<th>Title</th>
								<th>Category</th>
								<th>Action</th>
							</tr>
								


							<tr>
								<td>25-05-19</td>
								<td class="_table_name">Manhattan's art center "Shed" opening ceremony</td>
								<td>Economy</td>
								<td>
									<button class="_btn _action_btn view_btn1" type="button">View</button>
									<button class="_btn _action_btn edit_btn1" type="button">Edit</button>
									<button class="_btn _action_btn make_btn2" type="button">Make Features</button>
									<button class="_btn _action_btn make_btn3" type="button">Make Card</button>
									<button class="_btn _action_btn make_btn1" type="button">Delete</button>
								</td>
							</tr>
								

								
							<tr>
								<td>25-05-19</td>
								<td class="_table_name">Are Trump era is having an impact on what 's future voters</td>
								<td>Social</td>
								<td>
									<button class="_btn _action_btn view_btn1" type="button">View</button>
									<button class="_btn _action_btn edit_btn1" type="button">Edit</button>
									<button class="_btn _action_btn make_btn2" type="button">Make Features</button>
									<button class="_btn _action_btn make_btn3" type="button">Make Card</button>
									<button class="_btn _action_btn make_btn1" type="button">Delete</button>
								</td>
							</tr>
				


						</table>
					</div> -->
				
					
				
						<Collapse v-model="value" accordion>
							<Panel name="1">
								Add Tag
								<template #content>
									<Card style="width:100%;">
										<div style="text-align:center;">
											<img style="height:350px" src="/images/tag.png">

											<div class="bg-light p-3 mt-2 text-dark">
											<h4><li>Admin User Can Add Tag , Edit Tag , Delete Tag</li></h4>
											</div>

										</div>
									</Card>
								</template>
							</Panel>
							<Panel name="2">
								Add Category
								<template #content>
									<Card style="width:100%;">
										<div style="text-align:center">
											<img style="height:350px" src="/images/cat.png">

											<div class="bg-light p-3 mt-2 text-dark">
											<h4><li>Admin User Can Add Category , Edit Category , Delete Category</li></h4>
											</div>

										</div>
									</Card>
								</template>
							</Panel>
							<Panel name="3">
								Create Blog
								<template #content>

									<Card style="width:100%;">
										<div style="text-align:center;">
											<img style="height:280px" src="/images/bpost.png">

											<div class="bg-light p-3 mt-2 text-dark">
											<h4><li>Admin User Can Create Blog , Visite Blog , Edit Blog , Delete Blog</li></h4>
											</div>

										</div>
									</Card>
								</template>
							</Panel>
							<Panel name="4">
								Assining Role
								<template #content>
									<Card style="width:100%;">
										<div style="text-align:center;">
											<img style="height:480px" src="/images/arole.png">

											<div class="bg-light p-3 mt-2 text-dark">
											<h4>Role assigning for Which <b>Admin user</b> can perform which Task</h4>
											<p>You can also create a <b>Role</b> , then can create a <b>Admin User</b> for this <b>Role</b> </p>
											<p>Finally you can <b>Assign</b> specific <b>Role</b> Like,</p>
											<li>Which Menu List that User can <b>See</b> </li>
											<li>Which Menu List that User can<b>Create , Edit or Delete</b> </li>	
											</div>

										</div>
									</Card>
								</template>
							</Panel>
						</Collapse>

				</div>


				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<h1 style="text-align: center;color: #000; margin-bottom: 15px">Information</h1>

						<Tabs type="card" class="text-center">
							
							<TabPane label="Developer Info" name="name1">
								<Card :bordered="false">
									<template #title>
										<h3 class="text-left"> Amlan Kumar Mandol</h3>
									</template>
									<div class="d-flex align-items-center" >
										<Icon type="ios-mail-outline" size="32" color="#2d8cf0" />
										<h6 class="box text-dark ml-3 mb-1">amlankumar47@gmail.com</h6>
									</div>
									<div class="d-flex align-items-center" >
										<Icon type="ios-call-outline" size="32" color="#2d8cf0" />
										<h6 class="box text-dark ml-3 mb-1">+8801733472747</h6>
									</div>
									<div class="d-flex align-items-center" >
										<Icon type="ios-navigate-outline" size="32" color="#2d8cf0" />
										<h6 class="box text-dark ml-3 mb-1">Dhanmondhi,Dhaka</h6>
									</div>
									<div class="d-flex align-items-center" >
										<Icon type="ios-link-outline" size="32" color="#2d8cf0" />
										<a href="https://amlan-kumar-mandol.netlify.app/" target="_blank" class="box text-dark ml-3 mb-1" ><h6>https://amlan-kumar-mandol.netlify.app</h6></a>
									</div>
								</Card>

							</TabPane>

							<TabPane label="Fontend Tech" name="name2">
								<Card :bordered="false">
									<template #title>
										<h3 class="text-left">Fontend Development</h3>
									</template>
									<div class="d-flex align-items-center" >
										<Icon type="ios-qr-scanner" size="32" color="#025E73" />
										<h6 class="box text-dark ml-3 mb-1">HTML</h6>
									</div>
									<div class="d-flex align-items-center" >
										<Icon type="ios-nuclear-outline" size="32" color="#025E73" />
										<h6 class="box text-dark ml-3 mb-1">CSS</h6>
									</div>
									<div class="d-flex align-items-center" >
										<Icon type="ios-bowtie-outline" size="32" color="#025E73" />
										<h6 class="box text-dark ml-3 mb-1">Bootstarp</h6>
									</div>
									<div class="d-flex align-items-center" >
										<Icon type="ios-archive-outline" size="32" color="#025E73" />
										<h6 class="box text-dark ml-3 mb-1">Vue js</h6>
									</div>
									<div class="d-flex align-items-center" >
										<Icon type="ios-lock-outline" size="32" color="#025E73" />
										<h6 class="box text-dark ml-3 mb-1">iView</h6>
									</div>
									
								</Card>

							</TabPane>

							<TabPane label="Backend tech" name="name3">
								<Card :bordered="false">
									<template #title>
										<h3 class="text-left">Backend Development</h3>
									</template>
									<div class="d-flex align-items-center" >
										<Icon type="logo-nodejs" size="32" color="#F2668B" />
										<h6 class="box text-dark ml-3 mb-1">JS</h6>
									</div>
									<div class="d-flex align-items-center" >
										<Icon type="md-code-working" size="32" color="#F2668B" />
										<h6 class="box text-dark ml-3 mb-1">PHP</h6>
									</div>
									<div class="d-flex align-items-center" >
										<Icon type="ios-basket" size="32" color="#F2668B" />
										<h6 class="box text-dark ml-3 mb-1">Laravel</h6>
									</div>
					
								</Card>

							</TabPane>

							
						</Tabs>


				</div>
				 

			</div>
		</div>
	</div>
</template>


<script>
    export default {
        data () {
            return {
                value: ''
            }
        }
    }
</script>

			

