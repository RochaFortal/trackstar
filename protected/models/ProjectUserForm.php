<?php
class ProjectUserForm extends CFormModel
{
    public $username;
    
    public $role;
    
    public $project;
    
    public function rules()
    {
        return array(
            array('username, role','required'),
            array('username','exist','className'=>'User'),
            array('username','verify'),
            array('username, role, project','safe'),
        );
    }
    
    public function verify($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $user = User::model()->findByAttributes(array(
                'username'=>  $this->username,
            ));
            
            if($this->project->isUserInProject($user))
            {
                $this->addError('username','This user has already been added to the project.');
            }
            else
            {
                $this->project->associateUserToProject($user);
                $this->project->associateUserToRole($this->role,$user->id);
                
                $auth = Yii::app()->authManager;
                $bizRule = 'return isset($params["project"]) && 
                    $params["project"]->isUserInRole("'.$this->role.'");';
                $auth->assign($this->role,$user->id,$bizRule);
            }
        }
    }
}