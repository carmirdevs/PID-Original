<?php 
	class database{
		private $host = DB_HOST;
		private $user = DB_USER;
		private $password = DB_PASSWORD;
		private $name = DB_NAME;

		private $errores;

		public $dbh;
		public $stmt;

		public function __construct(){
            $this->errores = new errores();
			$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name;
			$opciones = array(
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);

			try {
				$this->dbh = new PDO($dsn,$this->user,$this->password,$opciones);
				$this->dbh->exec('set names utf8');
			} catch (PDOException $e) {
                $error_sql = $e->getMessage();
                $array_error_sql = explode(':',$error_sql);
                $this->errores->n_error_mysql =$array_error_sql[0];
                $this->errores->error_mysql =$array_error_sql[1];
                return $this->errores->datos(1,'Error, al conectar a la base de datos',
                    __CLASS__,__LINE__,__FILE__,$array_error_sql[2],__FUNCTION__);
			}
		}

		public function query($sql){
			$this->stmt = $this->dbh->prepare($sql);
		}

		public function execute(){
			$this->stmt->execute();
		}

        public function registros(){
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        }

		public function rowCount(){
			$this->execute();
			return $this->stmt->rowCount();
		}
	}
