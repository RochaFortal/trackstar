<?php

class RbacCommand extends CConsoleCommand
{
    private $_authManager;
    
    public function getHelp()
    {
        return "
        USAGE
            rbac
        DESCRIPTION
            This command generates an initial RBAC authorization hierarchy.
       ";
    }
    
    public function run($args)
    {
        if(($this->_authManager=Yii::app()->authManager)===null)
        {
            echo "Error: an authorization manager, 
                named 'authManager' must be configured to use this command.\n";
            echo "if you already added 'authManager' component in application configuration,\n";
            echo "please quit and re-enter the yiic shell.\n";
            return;
        }
        
        echo "This command will create three roles: Owner, Member, and 
            Reader and the following premissions:\n";
        echo "create, read, update and delete user\n";
        echo "create, read, update and delete project\n";
        echo "create, read, update and delete issue\n";
        echo "Would you like to continue? [Yes|No] ";
        
        
        if(!strncasecmp(trim(fgets(STDIN)), 'y', 1))
        {
            $this->_authManager->clearAll();
            
            $this->_authManager->createOperation("createUser","create a new user");
            $this->_authManager->createOperation("readUser","read 
                user profile information"); 
             $this->_authManager->createOperation("updateUser","update 
                a users information"); 
             $this->_authManager->createOperation("deleteUser","remove 
                a user from a project");
             
             $this->_authManager->createOperation("createProject","cre
                ate a new project"); 
             $this->_authManager->createOperation("readProject","read 
                project information"); 
              $this->_authManager->createOperation("updateProject","up
                date project information"); 
             $this->_authManager->createOperation("deleteProject","del
                ete a project"); 
             
             $this->_authManager->createOperation("createIssue","crea
                te a new issue"); 
             $this->_authManager->createOperation("readIssue","read 
                issue information"); 
             $this->_authManager->createOperation("updateIssue","upda
                te issue information"); 
             $this->_authManager->createOperation("deleteIssue","dele
                te an issue from a project");
             
             $role=$this->_authManager->createRole("reader"); 
             $role->addChild("readUser");
             $role->addChild("readProject"); 
             $role->addChild("readIssue");
             
             $role=$this->_authManager->createRole("member"); 
             $role->addChild("reader"); 
             $role->addChild("createIssue"); 
             $role->addChild("updateIssue"); 
             $role->addChild("deleteIssue");
             
             $role=$this->_authManager->createRole("owner"); 
             $role->addChild("reader"); 
             $role->addChild("member");    
             $role->addChild("createUser"); 
             $role->addChild("updateUser"); 
             $role->addChild("deleteUser");  
             $role->addChild("createProject"); 
             $role->addChild("updateProject"); 
             $role->addChild("deleteProject");
             
             echo "Authorization hierarchy successfully generated.";
        }
    }
}
?>
