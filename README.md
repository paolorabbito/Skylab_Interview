# Skylab-Interview

### Note:
    - A causa di problemi con SQLite3 sono stato costretto a ovviare caricando tutte le tabelle in MySQL
    - I file .sqlite e .sql si trovano nella cartella "DB File"
    - Nella cartella PDF si trovano il file con le richieste fatte dall'azienda e il file delle mie risposte alle domande
    - Il file query.php non è altro che un file dove sono andato a scrivere le due query sql richieste
    - Nel file route.php è implementato il sistema di routing
    - Nel file index.php vi sono le richieste a cui deve rispondere l'API
    - Nella cartella config è definito il file per l'interfaccia con il DB

### Test del progetto:
    * Importare in un database mysql il file data.sql
    * Inserire le corrette credenziali di accesso al DB in congif/Database.php
    * Lanciare l'applicativo con "php -S <addr>:<port>
    * Inoltrare le richieste http tramite un client REST

### Richieste:
    * GET http://<addr>:<port>/record/{int} => Ritorna il record denormalizzato con id specificato nell'URL in formato JSON
    * GET http://<addr>:<port>/records => Ritorna tutti i record denormalizzati in formato JSON
    * GET http://<addr>:<port>/statistics/type/[age | education_level_id | occupation_id ]/value/{int} => Ritorna delle statistiche filtrate sulla base dell'input dell' URL
    * GET http://<addr>:<port>/download Effettuata da browser permette di scaricare un file csv contenente tutti i record denormalizzati
    

