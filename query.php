<?php
    //Le due query richieste la punto 1 e 2 dell'esercizio
    //Trovare il numero di persone che hanno meno di 30 anni e percepiscono più di 50k annui
    $query_1 = "SELECT COUNT(*) as conteggio FROM records WHERE over_50k = true AND age < 30";
    //Trovare il guadagno capitale medio per ogni categoria lavorativa
    $query_2 = "SELECT avg(capital_gain) FROM records GROUP BY workclass_id";
    /*Per una questione di leggibilità del risultato la seconda query potrebbe essere implementata come segue
    $query_2bis = "SELECT name, avg(capital_gain) as guadagno_medio 
                   FROM records JOIN workclasses ON workclass_id = workclasses.id 
                   GROUP BY workclass_id";*/
    
?>