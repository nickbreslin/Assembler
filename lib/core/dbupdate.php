<?php

class Dbupdate extends Data
{
    private $changeFiles    = array();
    private $appliedChanges = array();
    private $unappliedFiles = array();
    private $path           = "";
    
    function __construct()
    {
        parent::__construct();
        $this->_init();
    }

    private function _init()
    {
        $this->path = ROOT_PATH . 'scripts/db-update/changes/';
        $this->schemaTableExists();
    }
    
    public function run()
    {
        Debug::info("Running...");
        //load file names
        $this->loadFiles();
        $this->loadApplied();
        $this->getUnapplied();
        $this->applyChanges();
        Debug::info("Complete.");
    }
    
    public function loadFiles()
    {
        $files = glob($this->path.'/*.php');
        
        $filenames = array();
        
        foreach ($files as $file) {
            $filename = basename($file);
            $filename = str_replace(".php" , "" , $filename);
            $filenames[] = $filename; 
        }

        $this->changeFiles = $filenames;
        Debug::info("Changes Files: \n".print_r($this->changeFiles, true));  
        
    }
    
    public function loadApplied()
    {
        $query = "SELECT change_filename FROM schema_versions_applied";
        
        if($rows = $this->_db->query($query))
        {
            foreach($rows as $row)
            {
                $this->appliedChanges[] = $row['change_filename'];
            }
            Debug::info("Previously Applied Changes: \n".print_r($this->appliedChanges, true));  
        }
    }
    
    public function getUnapplied()
    {
        $changes = array_diff($this->changeFiles, $this->appliedChanges);

         if (!count($changes))
         {
             Debug::info("No changes to apply."); 
         }
         else
 	    {
             foreach ($changes as $file)
             {
                 $this->unappliedFiles[] = $file;
             }
             Debug::info("Changes to be applied: \n".print_r($this->unappliedFiles, true));

         }
    }
    
    private function applyChanges()
    {
        foreach($this->unappliedFiles as $file)
        {
            $filePath = $this->path . $file . ".php";

            require $filePath;
            
            $class = 'change_'.$file;
            
            if(class_exists($class))
            {
                $change = new $class();
                if($change->run())
                {
					$this->record($file);
				}
            }
            else
            {
                Debug::error("Class <i>".$class."</i> not found.");
            }
        }
    }
    
    public function record($file)
    {
        $query = 'INSERT INTO schema_versions_applied (change_filename, applied) VALUES ("'.$file.'", '.time().')';
        
        if($result = $this->_db->query($query))
        {
            Debug::info("Recorded: ".$file);  
        }
        else
        {
            Debug::error("Not recorded: ".$file);
        }
    }
    
    private function schemaTableExists()
    {
		global $config;
	
        $query = "SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = '".$config['database.name']."'
        AND table_name = 'schema_versions_applied'";
        
        $result = $this->_db->query($query);

        if($result)
        {
            Debug::info("Schema_Versions_Applied table found.");
            return true;
        }
        
        Debug::error("Schema_Versions_Applied table not found.");
        
        $query = "
			CREATE TABLE `schema_versions_applied` (
            `change_id` int(11) NOT NULL AUTO_INCREMENT,
            `change_filename` varchar(255) NOT NULL,
            `applied` int(11) NOT NULL,
            PRIMARY KEY (`change_id`),
            UNIQUE KEY (`change_filename`)
            ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";
        
        $result = $this->_db->query($query);
        
        if($result)
        {
            Debug::info ("Schema_Versions_Applied table created.");
            return true;
        }
        
        return false;
    }
}