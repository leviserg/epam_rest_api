<?php
	namespace app\core;
	use app\core\View;

	class Router {

		protected $routes = [];
        protected $params = [];
		protected $selectedRoute = [];

		public function __construct() {
			$arr = require 'app/config/routes.php';
			foreach ($arr as $key => $val) {
				$this->addRoute($key, $val);
			}
        }

        public function addRoute($route, $params) {
            $route = preg_replace('/{([a-z]+):([^\}]+)}/', '(?P<\1>\2)', $route);
            $route = '#^'.$route.'$#';
			$this->routes[$route] = $params;
		}

		public function matchRoute() {
			$url = trim($_SERVER['REQUEST_URI'], '/');
			foreach ($this->routes as $route => $params) {
				if (preg_match($route, $url, $matches)) {
					foreach ($matches as $key => $match) {
						if (is_string($key)) {
							if (is_numeric($match)) {
								$match = (int) $match;
							}
							$params[$key] = $match;
                        }
					}
                    $this->params = $params;
                    $this->selectedRoute = $this->routes['#^'.$url.'$#'];
					return true;
                }
			}
            return false;
		}

		public function runApi(){
            if ($this->matchRoute()) {
				$path = 'app\core\models\\'.$this->params['model'];
				if (class_exists($path)) {
					$model = new $path();
					$action = $this->params['action'];
					if (method_exists($path, $action) && $this->params["method"] === "GET") { // get routes with data
						if(isset($this->params["id"])){
							$id = $this->params["id"];
							$data = $model->$action($id);
						}
						else{
							$data = $model->$action();
						}
						echo json_encode($data,JSON_PRETTY_PRINT);
					}
					elseif(method_exists($path, $action)){
						if(isset($this->params["id"])){
							$recordid = $this->params["id"];
							if($_SERVER['REQUEST_METHOD'] === $this->params["method"]){
								$_REQ = file_get_contents('php://input');
								$msg = ($this->params["method"] === "PUT") ? "changed" : "deleted";
								if(!empty($_REQ)){
									$ReqData = explode("\n",$_REQ);
									$body = $ReqData[3];
									$model->$action($recordid, $body);
									echo json_encode(['result'=> 'success','status'=>202,'data'=>'Record '.$recordid.' has been '.$msg],JSON_PRETTY_PRINT);									
								}
							}
							else{
								echo json_encode(['result'=> 'error','status'=>405,'data'=>'Method not allowed'],JSON_PRETTY_PRINT);
							}
						}
						else{
							if(!empty($_POST)){
								$model->$action($_POST['data']);
								echo json_encode(['result'=> 'success','status'=>201,'data'=>'New record has been inserted'],JSON_PRETTY_PRINT);								
							}
							else{
								echo json_encode(['result'=> 'error','status'=>204,'data'=>'Your data is empty'],JSON_PRETTY_PRINT);
							}
						}
					}
					else {
						echo json_encode(['result'=> 'error','status'=>405,'data'=>'Method not allowed'],JSON_PRETTY_PRINT);
					}

				} else {
					echo json_encode(['result'=> 'error','status'=>403,'data'=>"Forbidden. Model doesn't exist"],JSON_PRETTY_PRINT);
				}

			} else {
				echo json_encode(['result'=> 'error','status'=>400,'description'=>"Bad request. Route does'n exist"],JSON_PRETTY_PRINT);
			}
		}


	}