app.controller('LoginController', function($scope,$http){
	$scope.login = function(e){
		e.preventDefault();
		$http.post('login',$scope.form).then(function(response){
			if(response.data == 1){
				swal({
					title : "You have logged in",
					text : "Your credentials are right",
					icon : "success",
					closeOnClickOutside : false,
					closeOnEsc : false
				}).then( select => {
					if(select){
						location.reload();
					}
				});
			}else{
				swal('Your credentials are wrong', 'Try again', 'error');
				$scope.form.user = "";
				$scope.form.pass = "";
			}
		});
	}
});

app.controller('InvoiceController', function($scope,$http){
	$scope.titleModalUser = "";
	$scope.titleModalOrder = "";
	$scope.titleModalPayment = "";
	$scope.globalID = "";
	$scope.globalNumberOrder = "";
	$scope.globalIdOrder = "";
	$scope.userLogged = "";
	
	//MODELS OF TABLES
	$scope.modelUser = {
		identification : '',
		firstname : '',
		lastname : '',
		email : '',
		phone : '',
		user : '',
		password : '',
	}
	$scope.modelOrders = {
		number : '',
		status : 'O',
		userID : ''
	}
	$scope.modelPayments = {
		amount : '',
		date : '',
		orderID : '',
	}
	$scope.modelReports = {
		user  : '',
		order : ''
	}

	// FUNCTION TO GET ORDERS FROM ASSOCIATED CLIENTS
	// @Author : Jose Sanz - 13/07/2021
	$scope.getOrders = function(){
		$http.get('getOrders/'+$scope.modelReports.user).then(function(response){
			$scope.arrayOrders = response.data;
		});
	}

	// FUNCTION TO SET TITLE MODAL
	// @Author : Jose Sanz - 13/07/2021
	$scope.modal = function(type){
		$scope.titleModalOrder = type == 'create' ? 'Add Order' : 'Edit Order'; 
		$scope.titleModalUser = type == 'create' ? 'Add User' : 'Edit User'; 
		$scope.titleModalPayment = type == 'create' ? 'Add Payment' : 'Edit Payment'; 
	}

	// FUNCTION TO ESTABLISH THE ORDER MANAGEMENT
	// @Author : Jose Sanz - 13/07/2021
	$scope.setOrder = function(order,id){
		$scope.globalNumberOrder = '- '+order;
		$scope.modelPayments.orderID = id;
	}

	// FUNCTION TO SET METHOD DATA
	// @Author : Jose Sanz - 12/07/2021
	$scope.setData = function(event,table){
		if($scope.globalID != ''){
			$scope.update(event,table+'Update',table,$scope.globalID);
		}else{
			$scope.store(event,table+'Create',table);
		}
	} 

	// FUNCTION TO STORE DATA
	// @Author : Jose Sanz - 12/07/2021
	$scope.store = function(event,route,table){
		event.preventDefault();
		switch (table) {
			case 'users':
				$scope.model = $scope.modelUser;
				break;
			case 'orders':
				$scope.model = $scope.modelOrders;
				break;
			default:
				$scope.model = $scope.modelPayments;
				break;
		}
		$http.post(route,$scope.model).then(function(response){
			if(response.data == 1){
				swal({
					title : "Success",
					text : "data saved successfully",
					icon : "success",
					closeOnClickOutside : false,
					closeOnEsc : false
				}).then( select => {
					$scope.restarData(table);
					table == 'payments' ? $scope.getDataByID($scope.modelPayments.orderID,table,table) : $scope.getData(table);
					$(".modal").modal('hide');
				});
			}else{
				swal({
					title : "Attention",
					text : response.data == 0 ? "an error occurred, contact support" : "The payment amount exceeds the remain amount",
					icon : "error",
					closeOnClickOutside : false,
					closeOnEsc : false
				});
			}
		}, function (error){
			swal({
				title : "Attention",
				text : "an error occurred in database, please contact support",
				icon : "error",
				closeOnClickOutside : false,
				closeOnEsc : false
			})
			console.log(error);
		});
	}

	// FUNCTION TO UPDATE DATA
	// @Author : Jose Sanz - 12/07/2021
	$scope.update = function(event,route,table,id){
		event.preventDefault();
		switch (table) {
			case 'users':
				$scope.model = $scope.modelUser;
				break;
			case 'orders':
				$scope.model = $scope.modelOrders;
				break;
			default:
				$scope.model = $scope.modelPayments;
				$scope.modelPayments.date = new Date($scope.modelPayments.date);
				var day = $scope.modelPayments.date.getDate()-1; 
				$scope.modelPayments.date.setDate(day);
				break;
		}
		$http.put(route+'/'+id,$scope.model).then(function(response){
			if(response.data == 1){
				swal({
					title : "Success",
					text : "data successfully updated",
					icon : "success",
					closeOnClickOutside : false,
					closeOnEsc : false
				}).then( select => {
					table == 'payments' ? $scope.getDataByID($scope.modelPayments.orderID,table,table) : $scope.getData(table);
					$(".modal").modal('hide');
				});
			}else{
				swal({
					title : "Attention",
					text : "an error occurred, contact support",
					icon : "error",
					closeOnClickOutside : false,
					closeOnEsc : false
				}).then(select =>{
					$(".modal").modal('hide');
				});
			}
		}, function (error){
			swal({
				title : "Attention",
				text : "an error occurred in database, please contact support",
				icon : "error",
				closeOnClickOutside : false,
				closeOnEsc : false
			})
			console.log(error);
		});
	}

	// FUNCTION TO UPDATE DATA
	// @Author : Jose Sanz - 12/07/2021
	$scope.delete = function(event,table,id){
		event.preventDefault();
		swal({
			title : "Attention",
			text : "are you sure to delete the record ?",
			icon : "warning",
			buttons : {
				cancel : 'Cancel',
				Ok : true
			},
			closeOnClickOutside : false,
			closeOnEsc : false
		}).then( select => {
			switch (select) {
				case 'Ok':
					$http.delete(table+'Delete/'+id).then(function(response){
						if(response.data == 1){
							swal({
								title : "Success",
								text : "data delete successfully",
								icon : "success",
								closeOnClickOutside : false,
								closeOnEsc : false
							}).then( select => {
								table == 'payments' ? $scope.getDataByID($scope.modelPayments.orderID,table,table) : $scope.getData(table);
							});
						}else{
							swal({
								title : "Attention",
								text : "an error occurred, please contact support",
								icon : "error",
								closeOnClickOutside : false,
								closeOnEsc : false
							});
						}
					}, function (error){
						swal({
							title : "Attention",
							text : "an error occurred in database, please contact support",
							icon : "error",
							closeOnClickOutside : false,
							closeOnEsc : false
						})
						console.log(error);
					});
					break;
				default:
					break;
			}
		})
	}	

	// FUNCTION TO CLOSE ORDER
	// @Author : Jose Sanz - 13/07/2021
	$scope.closeOrder = function(event,id){
		event.preventDefault();
		swal({
			title : "Are you sure to close the invoice ?",
			text : "A new amount will be generated",
			icon : "warning",
			buttons : {
				cancel : 'Cancel',
				Ok : true
			},
			closeOnClickOutside : false,
			closeOnEsc : false
		}).then(select =>{
			switch (select) {
				case 'Ok':
					$http.put('ordersClose/'+id).then(function(response){
						swal({
							title : response.data == 1 ? "Success" : "Attention",
							text : response.data == 1 ? "Invoice closed correctly" : "an error occurred, contact support",
							icon : response.data == 1 ? "success" : "error",
							closeOnClickOutside : false,
							closeOnEsc : false
						}).then( select => {
							$scope.getData('orders');
						});
					}, function (error){
						swal('Attention', 'an error occurred in database, please contact support', 'error');
						console.log(error);
					});
					break;
				default:
					break;
			}
		})
	}

	// FUNCTION TO RESTART DATA OF MODELS
	// @Author : Jose Sanz - 12/07/2021
	$scope.restarData = function(table){
		$scope.globalID = '';
		switch (table) {
			case 'users':
				$scope.modelUser.identification = '';
				$scope.modelUser.firstname = '';
				$scope.modelUser.lastname = '';
				$scope.modelUser.email = '';
				$scope.modelUser.phone = '';
				$scope.modelUser.user = '';
				$scope.modelUser.password = '';
				break;
			case 'orders':
				$scope.modelOrders.number = '';
				$scope.modelOrders.userID = '';
				break;
			default:
				$scope.modelPayments.amount = "";
				$scope.modelPayments.date = "";
				break;
		}
	}

	// FUNCTION TO GET ALL DATA FROM REGISTER
	// @Author : Jose Sanz - 12/07/2021
	$scope.getDataByID = function(id,route,table){
		$http.get(route+'/'+id).then(function(response){
			switch (table) {
				case 'users':
					$scope.modelUser.identification = response.data[0].identification;
					$scope.modelUser.firstname = response.data[0].firstname;
					$scope.modelUser.lastname = response.data[0].lastname;
					$scope.modelUser.email = response.data[0].email;
					$scope.modelUser.phone = response.data[0].phone;
					$scope.modelUser.user = response.data[0].user;
					$scope.modelUser.password = '';
					$scope.globalID = response.data[0].id;
					break;
				case 'orders':
					$scope.modelOrders.number = response.data[0].number;
					$scope.modelOrders.userID = response.data[0].userID;
					$scope.globalID = response.data[0].id;
					break;
					case 'payment':
					$scope.globalID = response.data[0].id;
					$scope.modelPayments.amount = parseFloat(response.data[0].amount);
					response.data[0].date = new Date(response.data[0].date);
					var day = response.data[0].date.getDate()+1; 
					response.data[0].date.setDate(day);
					$scope.modelPayments.date = response.data[0].date;
					break;
				default:
					$scope.invoices = response.data.invoice;
					$scope.payments = response.data.payments;
					break;
			}
		});
	}

	// FUNCTION TO GET ALL DATA FROM TABLE
	// @Author : Jose Sanz - 12/07/2021
	$scope.getData = function($table){
		$http.get($table).then(function(response){
			switch ($table) {
				case 'users':
					if($scope.userLogged == ''){
						$scope.userLogged = response.data.userLog;
					}
					$scope.users = response.data.data;
					break;
				case 'orders':
					$scope.orders = response.data;
					break;
				default:
					//$scope.payments = response.data;
					break;
			}
		});
	}

	// FUNCTION OF LOGOUT THE SYSTEM
	// @Author : Jose Sanz - 12/07/2021
	$scope.logout = function(){
		$http.get('logout').then(function(response){
			if(response.data == 1){
				location.reload();
			}
		});
	}

	$scope.getData('users');
	$scope.getData('orders');
});