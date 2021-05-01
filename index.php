<?php
    require_once 'route.php';
    include_once './config/Database.php';

    //Ritorna tutti i records denormalizzati in formato JSON
    route('records', function (){
        $database = new Database();
        $db = $database->connect();

        $query = 'SELECT records.id, age, workclass_id, w.name as workclass, education_level_id, e.name as education_level, 
              education_num, marital_status_id, m.name as marital_status, occupation_id, o.name as occupation, race_id, 
              r.name as race, sex_id, s.name as sex, capital_gain, capital_loss, hours_week, country_id, c.name as country,
              over_50k
              FROM records JOIN countries c ON country_id = c.id
                           JOIN education_levels e ON education_level_id = e.id
                           JOIN marital_statuses m ON marital_status_id = m.id
                           JOIN occupations o ON occupation_id = o.id
                           JOIN races r ON race_id = r.id
                           JOIN sexes s ON sex_id = s.id
                           JOIN workclasses w ON workclass_id = w.id';

        $result = $db->query($query);
        $users = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $users[$row['id']] = array(
                'id' => $row['id'],
                'age' => $row['age'],
                'workclass_id' => $row['workclass_id'],
                'workclass' => $row['workclass'],
                'education_level_id' => $row['education_level_id'],
                'education_level' => $row['education_level'],
                'education_num' => $row['education_num'],
                'marital_status_id' => $row['marital_status_id'],
                'marital_status' => $row['marital_status'],
                'occupation_id' => $row['occupation_id'],
                'occupation' => $row['occupation'],
                'race_id' => $row['race_id'],
                'race' => $row['race'],
                'sex_id' => $row['sex_id'],
                'sex' => $row['sex'],
                'capital_gain' => $row['capital_gain'],
                'capital_loss' => $row['capital_loss'],
                'country_id' => $row['country_id'],
                'country' => $row['country'],
                'over_50k' => $row['over_50k'],
            );
        } 
        $database->close();
        header('Content-Type: application/json');
        return json_encode($users);
    });

    //Ritorna il record identificato dal paramentro denormalizzato in formato JSON (non mi era chiaro il concetto di paginazione parametrica con count e offset)
    route('record/(.+)', function ($id){
        $database = new Database();
        $db = $database->connect();

        $query = "SELECT records.id, age, workclass_id, w.name as workclass, education_level_id, e.name as education_level, 
              education_num, marital_status_id, m.name as marital_status, occupation_id, o.name as occupation, race_id, 
              r.name as race, sex_id, s.name as sex, capital_gain, capital_loss, hours_week, country_id, c.name as country,
              over_50k
              FROM records JOIN countries c ON country_id = c.id
                           JOIN education_levels e ON education_level_id = e.id
                           JOIN marital_statuses m ON marital_status_id = m.id
                           JOIN occupations o ON occupation_id = o.id
                           JOIN races r ON race_id = r.id
                           JOIN sexes s ON sex_id = s.id
                           JOIN workclasses w ON workclass_id = w.id
               WHERE records.id = $id";

        $result = $db->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $users = array();
        
        $users[$row['id']] = array(
            'id' => $row['id'],
            'age' => $row['age'],
            'workclass_id' => $row['workclass_id'],
            'workclass' => $row['workclass'],
            'education_level_id' => $row['education_level_id'],
            'education_level' => $row['education_level'],
            'education_num' => $row['education_num'],
            'marital_status_id' => $row['marital_status_id'],
            'marital_status' => $row['marital_status'],
            'occupation_id' => $row['occupation_id'],
            'occupation' => $row['occupation'],
            'race_id' => $row['race_id'],
            'race' => $row['race'],
            'sex_id' => $row['sex_id'],
            'sex' => $row['sex'],
            'capital_gain' => $row['capital_gain'],
            'capital_loss' => $row['capital_loss'],
            'country_id' => $row['country_id'],
            'country' => $row['country'],
            'over_50k' => $row['over_50k'],
        );
        
        $database->close();
        header('Content-Type: application/json');
        return json_encode($users);
    });

    //Ritorna le statistiche filtrate in base ai parametri usati 
    //ESEMPIO: GET http://localhost:8080/statistics/type/[age | education_level_id | occupation_id ]/value/{intero}
    route('statistics/type/(.+)/value/(.*)', function ($type, $value){
        $database = new Database();
        $db = $database->connect();

        $value = intval($value);

        $query1 = "SELECT SUM(capital_gain) as somma_guadagni,
                         AVG(capital_gain) as media_guadagni,
                         SUM(capital_loss) as somma_perdite,
                         AVG(capital_loss) as media_perdite
                  FROM records 
                  WHERE $type = $value";

        $query2 = "SELECT COUNT(*) as count FROM records WHERE $type = $value and over_50k=1";

        $query3 = "SELECT COUNT(*) as count FROM records WHERE $type = $value and over_50k=0";
                  
        $result1 = $db->query($query1);
        $result2 = $db->query($query2);
        $result3 = $db->query($query3);

        $row1 = $result1->fetch(PDO::FETCH_ASSOC);
        $row2 = $result2->fetch(PDO::FETCH_ASSOC);
        $row3 = $result3->fetch(PDO::FETCH_ASSOC);
        
        $statitics = array(
            'somma_guadagni' => $row1['somma_guadagni'],
            'media_guadagni' => $row1['media_guadagni'],
            'somma_perdite' => $row1['somma_perdite'],
            'media_perdite' => $row1['media_perdite'],
            'over_50k' => $row2['count'],
            'under_50k' => $row3['count'],
        );

        $database->close();
        header('Content-Type: application/json');
        return json_encode($statitics);
    });

    //Scrive tutti i records denormalizzati in un file in formato CSV e ne avvia il download
    route('download', function (){
        $database = new Database();
        $db = $database->connect();

        $query = 'SELECT records.id, age, workclass_id, w.name as workclass, education_level_id, e.name as education_level, 
              education_num, marital_status_id, m.name as marital_status, occupation_id, o.name as occupation, race_id, 
              r.name as race, sex_id, s.name as sex, capital_gain, capital_loss, hours_week, country_id, c.name as country,
              over_50k
              FROM records JOIN countries c ON country_id = c.id
                           JOIN education_levels e ON education_level_id = e.id
                           JOIN marital_statuses m ON marital_status_id = m.id
                           JOIN occupations o ON occupation_id = o.id
                           JOIN races r ON race_id = r.id
                           JOIN sexes s ON sex_id = s.id
                           JOIN workclasses w ON workclass_id = w.id';

        $result = $db->query($query);
        $users = array();
        $file = fopen("file.csv", "w");
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            
            fputcsv($file, $row);
        } 
        $database->close();

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename('file.csv'));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('file.csv'));
        readfile("file.csv");
        exit;
        
    });
   

    $action = $_SERVER['REQUEST_URI'];
    dispatch($action);