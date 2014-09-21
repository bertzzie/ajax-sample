<?php

include_once 'epi/Epi.php';

Epi::init('route');
Epi::init('database');

Epi::setSetting('exceptions', true);

EpiDatabase::employ('mysql', 'employees', 'localhost', 'root', '');

$router = new EpiRoute();
$routes = AjaxAPI::$routes;
$rmeth  = AjaxAPI::$routeMethods;

foreach ($routes as $key => $value) {
	if (array_key_exists($value['rel'], $rmeth)) {
		switch ($value['method']) {
			case 'GET':
				$router->get($value['href'], array('AjaxAPI', $rmeth[$value['rel']]));
				break;
			case 'POST':
				$router->post($value['href'], array('AjaxAPI', $rmeth[$value['rel']]));
				break;
			case 'PUT':
				$router->put($value['href'], array('AjaxAPI', $rmeth[$value['rel']]));
				break;
			case 'DELETE':
				$router->delete($value['href'], array('AjaxAPI', $rmeth[$value['rel']]));
				break;
		}
	}
}

$router->run();

class AjaxAPI {
	public static $routes = [
		['rel' => 'root', 'method' => 'GET', 'href' => '/'],

		['rel' => 'list departments', 'method'   => 'GET',    'href' => '/departments'],
		['rel' => 'show departments', 'method'   => 'GET',    'href' => '/departments/(\w{4})'],
		['rel' => 'add departments', 'method'    => 'PUT',    'href' => '/departments/(\w{4})'],
		['rel' => 'update departments', 'method' => 'POST',   'href' => '/departments/(\w{4})'],
		['rel' => 'delete departments', 'method' => 'DELETE', 'href' => '/departments/(\w{4})'],

		['rel' => 'list dept emp', 'method' => 'GET',    'href' => '/departments/(\w{4})/employees'],
		['rel' => 'add dept emp', 'method' => 'PUT',    'href' => '/departments/(\w{4})/employees'],
		['rel' => 'update dept emp', 'method' => 'POST',   'href' => '/departments/(\w{4})/employees'],
		['rel' => 'delete dept emp', 'method' => 'DELETE', 'href' => '/departments/(\w{4})/employees'],

		['rel' => 'list dept man', 'method' => 'GET',    'href' => '/departments/(\w{4})/managers'],
		['rel' => 'add dept man', 'method' => 'PUT',    'href' => '/departments/(\w{4})/managers'],
		['rel' => 'update dept man', 'method' => 'POST',   'href' => '/departments/(\w{4})/managers'],
		['rel' => 'delete dept man', 'method' => 'DELETE', 'href' => '/departments/(\w{4})/managers'],

		['rel' => 'list employees', 'method'   => 'GET',    'href' => '/employees'],
		['rel' => 'list employees n', 'method'   => 'GET',    'href' => '/employees/(\d+)'],
		['rel' => 'show employees', 'method'   => 'GET',    'href' => '/employees/(\d+)/profile'],
		['rel' => 'add employees', 'method'    => 'PUT',    'href' => '/employees/(\d+)'],
		['rel' => 'update employees', 'method' => 'POST',   'href' => '/employees/(\d+)'],
		['rel' => 'delete employees', 'method' => 'DELETE', 'href' => '/employees/(\d+)'],

		['rel' => 'list emp sal', 'method' => 'GET',    'href' => '/employees/(\d+)/salaries'],
		['rel' => 'add emp sal', 'method' => 'PUT',    'href' => '/employees/(\d+)/salaries'],
		['rel' => 'update emp sal', 'method' => 'POST',   'href' => '/employees/(\d+)/salaries'],
		['rel' => 'delete emp sal', 'method' => 'DELETE', 'href' => '/employees/(\d+)/salaries'],

		['rel' => 'list emp title', 'method' => 'GET',    'href' => '/employees/(\d+)/titles'],
		['rel' => 'add emp title', 'method' => 'PUT',    'href' => '/employees/(\d+)/titles'],
		['rel' => 'update emp title', 'method' => 'POST',   'href' => '/employees/(\d+)/titles'],
		['rel' => 'delete emp title', 'method' => 'DELETE', 'href' => '/employees/(\d+)/titles'],
	];

	public static $routeMethods = [
		'root'               => 'MethodList',
		'list departments'   => 'DepartmentList',
		'show departments'   => 'DepartmentShow',
		'add departments'    => 'DepartmentAdd',
		'update departments' => 'DepartmentUpdate',
		'delete departments' => 'DepartmentDelete',
		'list dept emp'      => 'DepartmentEmployeeList',
		'add dept emp'       => 'DepartmentEmployeeAdd',
		'update dept emp'    => 'DepartmentEmployeeUpdate',
		'delete dept emp'    => 'DepartmentEmployeeDelete',
		'list dept man'      => 'DepartmentManagerList',
		'add dept man'       => 'DepartmentManagerAdd',
		'update dept man'    => 'DepartmentManagerUpdate',
		'delete dept man'    => 'DepartmentManagerDelete',
		'list employees'     => 'EmployeeList',
		'list employees n'   => 'EmployeeListN',
		'show employees'     => 'EmployeeShow',
		'add employees'      => 'EmployeeAdd',
		'update employees'   => 'EmployeeUpdate',
		'delete employees'   => 'EmployeeDelete',
		'list emp sal'       => 'EmployeeSalaryList',
		'add emp sal'        => 'EmployeeSalaryAdd',
		'update emp sal'     => 'EmployeeSalaryUpdate',
		'delete emp sal'     => 'EmployeeSalaryDelete',
		'list emp title'     => 'EmployeeTitleList',
		'add emp title'      => 'EmployeeTitleAdd',
		'update emp title'   => 'EmployeeTitleUpdate',
		'delete emp title'   => 'EmployeeTitleDelete'
	];

	public static function getNewDeptNo() {
		$old    = AjaxAPI::getLastDeptNo();
		$num    = intval(substr($old, 1));
		$new    = $num + 1;
		$zeroes = str_repeat("0", 3 - strlen(strval($new)));

		return 'd' . $zeroes . strval($new);
	}

	public static function getLastDeptNo() {
		$query = "SELECT * FROM departments d ORDER BY d.dept_no DESC";
		$data  = getDatabase()->one($query);

		return $data['dept_no'];
	}

	public static function getNewEmployeeNo() {
		$old = intval(AjaxAPI::getLastEmployeeNo());
		$new = $old + 1;

		return $new;
	}

	public static function getLastEmployeeNo() {
		$query = "SELECT e.emp_no FROM employees e ORDER BY e.emp_no DESC";
		$data  = getDatabase()->one($query);

		return $data['emp_no'];
	}

	public static function MethodList() {
		$result = array('version' => '1.0', 'links' => AjaxAPI::$routes);

		echo json_encode($result);
	}

	public static function DepartmentList() {
		$results = getDatabase()->all("SELECT * FROM departments");

		echo json_encode($results);
	}

	public static function DepartmentShow($id) {
		$query = "SELECT * FROM departments WHERE dept_no = :dept_no";

		try {
			$data  = getDatabase()->one($query, array(':dept_no' => $id));
			$no    = $data['dept_no'];
			$name  = $data['dept_name'];

			$result = [
				"departments" => ["dept_no" => $no, "dept_name" => $name],
				"links" => [
					["href"  => "/departments",
					"rel"    => "list departments",
					"method" => "GET"],
					["href"  => "/departments/" . $no,
					"rel"    => "show departments",
					"method" => "GET"],
					["href"  => "/departments/" . AjaxAPI::getNewDeptNo(),
					"rel"    => "add departments",
					"method" => "PUT"],
					["href"  => "/departments/" . $no,
					"rel"    => "update departments",
					"method" => "UPDATE"],
					["href"  => "/departments/" . $no,
					"rel"    => "delete departments",
					"method" => "DELETE"]
				]
			];

			echo json_encode($result);
		} catch (Exception $e) {
			http_response_code(500);
			echo "Get data failed.";
		}
	}

	public static function DepartmentAdd($id) {
		$query = "INSERT INTO departments(dept_no, dept_name) VALUES (:dept_no, :dept_name)";
		$data  = json_decode(file_get_contents("php://input"), true);
		$name  = $data["dept_name"];

		try {
			getDatabase()->execute($query, array(':dept_no' => $id, ':dept_name' => $name));

			$result = [
				"departments" => ["dept_no" => $id, "dept_name" => $name],
				"links" => [
					["href"  => "/departments",
					"rel"    => "list departments",
					"method" => "GET"],
					["href"  => "/departments/" . $id,
					"rel"    => "show departments",
					"method" => "GET"],
					["href"  => "/departments/" . AjaxAPI::getNewDeptNo(),
					"rel"    => "add departments",
					"method" => "PUT"],
					["href"  => "/departments/" . $id,
					"rel"    => "update departments",
					"method" => "UPDATE"],
					["href"  => "/departments/" . $id,
					"rel"    => "delete departments",
					"method" => "DELETE"]
				]
			];

			echo json_encode($result);
		} catch (Exception $e) {
			http_response_code(500);
			echo "Insert Failed";
		}
	}

	public static function DepartmentUpdate($id) {
		$query = "UPDATE departments SET dept_name = :dept_name WHERE dept_no = :dept_no";
		$data  = json_decode(file_get_contents("php://input"), true);
		$name  = $data["dept_name"];

		try {
			getDatabase()->execute($query, array(':dept_no' => $id, ':dept_name' => $name));

			$result = [
				"departments" => ["dept_no" => $id, "dept_name" => $name],
				"links" => [
					["href"  => "/departments",
					"rel"    => "list departments",
					"method" => "GET"],
					["href"  => "/departments/" . $id,
					"rel"    => "show departments",
					"method" => "GET"],
					["href"  => "/departments/" . AjaxAPI::getNewDeptNo(),
					"rel"    => "add departments",
					"method" => "PUT"],
					["href"  => "/departments/" . $id,
					"rel"    => "update departments",
					"method" => "UPDATE"],
					["href"  => "/departments/" . $id,
					"rel"    => "delete departments",
					"method" => "DELETE"]
				]
			];

			echo json_encode($result);
		} catch (Exception $e) {
			http_response_code(500);
			echo "Update Failed";
		}
	}

	public static function DepartmentDelete($id) {
		$query = "DELETE FROM departments WHERE dept_no = :dept_no";

		try {
			getDatabase()->execute($query, array(':dept_no' => $id));

			$result = [
				"links" => [
					["href"  => "/departments",
					"rel"    => "list departments",
					"method" => "GET"],
					["href"  => "/departments/" . $id,
					"rel"    => "show departments",
					"method" => "GET"],
					["href"  => "/departments/" . AjaxAPI::getNewDeptNo(),
					"rel"    => "add departments",
					"method" => "PUT"],
					["href"  => "/departments/" . $id,
					"rel"    => "update departments",
					"method" => "UPDATE"],
					["href"  => "/departments/" . $id,
					"rel"    => "delete departments",
					"method" => "DELETE"]
				]
			];

			echo json_encode($result);
		} catch (Exception $e) {
			http_response_code(500);
			echo "Delete Failed.";
		}
	}

	public static function EmployeeList() {
		$results = getDatabase()->all("SELECT * FROM employees e ORDER BY e.emp_no DESC LIMIT 0, 25");

		$newEmpNo = AjaxAPI::getNewEmployeeNo();
		foreach ($results as $key => &$result) {
			$empNo = $result['emp_no'];
			$result["links"] = [
					["href"   => "/employees",
					 "rel"    => "list employees",
					 "method" => "GET"],
					["href"   => "/employees/" . $empNo,
					 "rel"    => "show employees",
					 "method" => "GET"],
					["href"   => "/employees/" . $newEmpNo,
					 "rel"    => "add employees",
					 "method" => "PUT"],
					["href"   => "/employees/" . $empNo,
					 "rel"    => "update employees",
					 "method" => "POST"],
					["href"   => "/employees/" . $empNo,
					 "rel"    => "delete employees",
					 "method" => "DELETE"],
			];
		}

		echo json_encode($results);
	}

	public static function EmployeeListN($page) {
        $query = "SELECT * FROM employees e ORDER BY e.emp_no DESC LIMIT :offset, :perpage";
		$results = getDatabase()->all($query, array(":offset" => ($page - 1) * 25, ":perpage" => 25));

		$newEmpNo = AjaxAPI::getNewEmployeeNo();
		foreach ($results as $key => &$result) {
			$empNo = $result['emp_no'];
			$result["links"] = [
					["href"   => "/employees",
					 "rel"    => "list employees",
					 "method" => "GET"],
					["href"   => "/employees/" . $empNo,
					 "rel"    => "show employees",
					 "method" => "GET"],
					["href"   => "/employees/" . $newEmpNo,
					 "rel"    => "add employees",
					 "method" => "PUT"],
					["href"   => "/employees/" . $empNo,
					 "rel"    => "update employees",
					 "method" => "POST"],
					["href"   => "/employees/" . $empNo,
					 "rel"    => "delete employees",
					 "method" => "DELETE"],
			];
		}

		echo json_encode($results);
	}

	public static function EmployeeShow($id) {
		$query = "SELECT * FROM employees WHERE emp_no = :emp_no";

		try {
			$data  = getDatabase()->one($query, array(':emp_no' => $id));
			$no    = $data['emp_no'];

			$result = [
				"employee" => $data,
				"links" => [
					["href"  => "/employees",
					"rel"    => "list employees",
					"method" => "GET"],
					["href"  => "/employees/" . $no,
					"rel"    => "show employees",
					"method" => "GET"],
					["href"  => "/employees/" . AjaxAPI::getNewEmployeeNo(),
					"rel"    => "add employees",
					"method" => "PUT"],
					["href"  => "/employees/" . $no,
					"rel"    => "update employees",
					"method" => "UPDATE"],
					["href"  => "/employees/" . $no,
					"rel"    => "delete employees",
					"method" => "DELETE"]
				]
			];

			echo json_encode($result);
		} catch (Exception $e) {
			http_response_code(500);
			echo "Get data failed.";
		}
	}

	public static function EmployeeAdd($id) {
		$query = "INSERT INTO employees(emp_no, birth_date, first_name, last_name, gender, hire_date) 
		          VALUES (:emp_no, :birth_date, :first_name, :last_name, :gender, :hire_date)";

		$data  = json_decode(file_get_contents("php://input"), true);
		$no    = $id;

		try {
			getDatabase()->execute($query, array(
				":emp_no"     => $no,
				":birth_date" => $data["birth_date"],
				":first_name" => $data["first_name"],
				":last_name"  => $data["last_name"],
				":gender"     => $data["gender"],
				":hire_date"  => $data["hire_date"]
			));

			$result = [
				"employees" => [
					"emp_no"     => $no,
					"birth_date" => $data["birth_date"],
					"first_name" => $data["first_name"],
					"last_name"  => $data["last_name"],
					"gender"     => $data["gender"],
					"hire_date"  => $data["hire_date"]
				],
				"links" => [
					["href"  => "/employees",
					"rel"    => "list employees",
					"method" => "GET"],
					["href"  => "/employees/" . $no,
					"rel"    => "show employees",
					"method" => "GET"],
					["href"  => "/employees/" . AjaxAPI::getNewEmployeeNo(),
					"rel"    => "add employees",
					"method" => "PUT"],
					["href"  => "/employees/" . $no,
					"rel"    => "update employees",
					"method" => "UPDATE"],
					["href"  => "/employees/" . $no,
					"rel"    => "delete employees",
					"method" => "DELETE"]
				]
			];

			echo json_encode($result);
		} catch (Exception $e) {
			http_response_code(500);
			echo "Insert Failed";
		}
	}

	public static function EmployeeUpdate($id) {
		$query = "UPDATE employees 
		          SET birth_date = :birth_date, 
		              first_name = :first_name, 
		              last_name = :last_name, 
		              gender = :gender, 
		              hire_date = :hire_date
		          WHERE emp_no = :emp_no";
		$data  = json_decode(file_get_contents("php://input"), true);
		$no    = $id;

		try {
			getDatabase()->execute($query, array(
				":emp_no"     => $no,
				":birth_date" => $data["birth_date"],
				":first_name" => $data["first_name"],
				":last_name"  => $data["last_name"],
				":gender"     => $data["gender"],
				":hire_date"  => $data["hire_date"]
			));

			$result = [
				"employees" => [
					"emp_no"     => $no,
					"birth_date" => $data["birth_date"],
					"first_name" => $data["first_name"],
					"last_name"  => $data["last_name"],
					"gender"     => $data["gender"],
					"hire_date"  => $data["hire_date"]
				],
				"links" => [
					["href"  => "/employees",
					"rel"    => "list employees",
					"method" => "GET"],
					["href"  => "/employees/" . $no,
					"rel"    => "show employees",
					"method" => "GET"],
					["href"  => "/employees/" . AjaxAPI::getNewEmployeeNo(),
					"rel"    => "add employees",
					"method" => "PUT"],
					["href"  => "/employees/" . $no,
					"rel"    => "update employees",
					"method" => "UPDATE"],
					["href"  => "/employees/" . $no,
					"rel"    => "delete employees",
					"method" => "DELETE"]
				]
			];

			echo json_encode($result);
		} catch (Exception $e) {
			http_response_code(500);
			echo "Update Failed";
		}
	}

	public static function EmployeeDelete($id) {
		$query = "DELETE FROM employees WHERE emp_no = :emp_no";
		$no    = $id;

		try {
			getDatabase()->execute($query, array(':emp_no' => $id));

			$result = [
				"links" => [
					["href"  => "/employees",
					"rel"    => "list employees",
					"method" => "GET"],
					["href"  => "/employees/" . $no,
					"rel"    => "show employees",
					"method" => "GET"],
					["href"  => "/employees/" . AjaxAPI::getNewEmployeeNo(),
					"rel"    => "add employees",
					"method" => "PUT"],
					["href"  => "/employees/" . $no,
					"rel"    => "update employees",
					"method" => "UPDATE"],
					["href"  => "/employees/" . $no,
					"rel"    => "delete employees",
					"method" => "DELETE"]
				]
			];

			echo json_encode($result);
		} catch (Exception $e) {
			http_response_code(500);
			echo "Delete Failed.";
		}
	}
}
