<?php
    class Database{
        //Modificare i parametri sottostanti per connettersi al proprio database locale
        private $host = 'localhost'; 
        private $username = 'test'; 
        private $password = 'test'; 
        private $db = 'skylab';

        //Viene ritornata dalla funzione connect come elemento di tramite per interagire con il database
        private $connection;

        public function connect() {
            $this->connection = null;

            try{
                //Modificare mysql con il proprio DBMS
                $this->connection = new PDO('mysql:host=' . $this->host 
                                            . ';dbname=' . $this->db, 
                                            $this->username, $this->password);
                
            }catch(PDOException $e){
                echo $e->getMessage();
            }

            return $this->connection;
        }

        public function close(){
           $this->connection = null;
        }
    }
