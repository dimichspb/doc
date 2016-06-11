<?php
namespace app\commands;

use Yii;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\console\Controller;
use app\models\User;

class RbacController extends Controller
{

    const ADMIN_ROLE_NAME = 'Admin';
    const SUPPLIER_ROLE_NAME = 'Supplier';
    const CUSTOMER_ROLE_NAME = 'Customer';

    const BASE_USER_USERNAME = 'baseuser';
    const BASE_USER_PASSWORD = 'basepassword';

    private $adminRole;
    private $supplierRole;
    private $customerRole;

    private $companyPermissions;
    private $customerPermissions;
    private $entityPermissions;
    private $supplierPermissions;
    private $userPermissions;
    private $productPermissions;
    private $pricePermissions;
    private $requestPermissions;
    private $quotationPermissions;
    private $orderPermissions;
    private $stockPermissions;
    private $deliveryPermissions;


    /**
     * Action adds all necessary roles and permissions
     *
     */
    public function actionInit()
    {
        $this->actionRemoveAll();
        $this->actionAddPermissions();
        $this->actionAddRoles();
    }


    public function actionAddPermissions()
    {
        $this->companyPermissions = $this->createPermissions('Company');
        $this->customerPermissions = $this->createPermissions('Customer');
        $this->entityPermissions = $this->createPermissions('Entity');
        $this->supplierPermissions = $this->createPermissions('Supplier');
        $this->userPermissions = $this->createPermissions('User');
        $this->productPermissions = $this->createPermissions('Product');
        $this->pricePermissions = $this->createPermissions('Price');
        $this->requestPermissions = $this->createPermissions('Request');
        $this->quotationPermissions = $this->createPermissions('Quotation');
        $this->orderPermissions = $this->createPermissions('Order');
        $this->stockPermissions = $this->createPermissions('Stock');
        $this->deliveryPermissions = $this->createPermissions('Delivery');
    }

    public function actionAddRoles()
    {
        $this->actionAddAdminRole();
        $this->actionAddSupplierRole();
        $this->actionAddCustomerRole();

        $baseUser = $this->addBaseUser();

        if ($baseUser) {
            $this->assignRole($baseUser, $this->adminRole);
        }
    }

    /**
     * Action adds Admin role with specific permissions
     *
     */
    public function actionAddAdminRole()
    {
        $this->adminRole = $this->createRole(self::ADMIN_ROLE_NAME, [
            $this->companyPermissions['getList'],
            $this->companyPermissions['getOne'],
            $this->companyPermissions['createOne'],
            $this->companyPermissions['updateOne'],
            $this->companyPermissions['deleteOne'],

            $this->entityPermissions['getList'],
            $this->entityPermissions['getOne'],
            $this->entityPermissions['updateOne'],
            $this->entityPermissions['createOne'],
            $this->entityPermissions['deleteOne'],

            $this->customerPermissions['getList'],
            $this->customerPermissions['getOne'],
            $this->customerPermissions['updateOne'],
            $this->customerPermissions['createOne'],
            $this->customerPermissions['deleteOne'],

            $this->supplierPermissions['getList'],
            $this->supplierPermissions['getOne'],
            $this->supplierPermissions['updateOne'],
            $this->supplierPermissions['createOne'],
            $this->supplierPermissions['deleteOne'],

            $this->userPermissions['getList'],
            $this->userPermissions['getOne'],
            $this->userPermissions['createOne'],
            $this->userPermissions['updateOne'],
            $this->userPermissions['deleteOne'],

            $this->productPermissions['getList'],
            $this->productPermissions['getOne'],
            $this->productPermissions['createOne'],
            $this->productPermissions['updateOne'],
            $this->productPermissions['deleteOne'],

            $this->pricePermissions['getList'],
            $this->pricePermissions['getOne'],
            $this->pricePermissions['createOne'],
            $this->pricePermissions['updateOne'],
            $this->pricePermissions['deleteOne'],

            $this->requestPermissions['getList'],
            $this->requestPermissions['getOne'],
            $this->requestPermissions['createOne'],
            $this->requestPermissions['updateOne'],
            $this->requestPermissions['deleteOne'],

            $this->quotationPermissions['getList'],
            $this->quotationPermissions['getOne'],
            $this->quotationPermissions['createOne'],
            $this->quotationPermissions['updateOne'],
            $this->quotationPermissions['deleteOne'],

            $this->orderPermissions['getList'],
            $this->orderPermissions['getOne'],
            $this->orderPermissions['createOne'],
            $this->orderPermissions['updateOne'],
            $this->orderPermissions['deleteOne'],

            $this->stockPermissions['getList'],
            $this->stockPermissions['getOne'],
            $this->stockPermissions['createOne'],
            $this->stockPermissions['updateOne'],
            $this->stockPermissions['deleteOne'],

            $this->deliveryPermissions['getList'],
            $this->deliveryPermissions['getOne'],
            $this->deliveryPermissions['createOne'],
            $this->deliveryPermissions['updateOne'],
            $this->deliveryPermissions['deleteOne'],

        ], self::ADMIN_ROLE_NAME . ' role');
    }

    /**
     * Action adds Admin role with specific permissions
     *
     */
    public function actionAddSupplierRole()
    {
        $this->supplierRole = $this->createRole(self::SUPPLIER_ROLE_NAME, [
            $this->productPermissions['getList'],
            $this->productPermissions['getOne'],

//            $this->entityPermissions['getList'],
//            $this->entityPermissions['getOne'],
//            $this->entityPermissions['updateOne'],
//            $this->entityPermissions['createOne'],

//            $this->customerPermissions['getList'],
//            $this->customerPermissions['getOne'],
//            $this->customerPermissions['updateOne'],
//            $this->customerPermissions['createOne'],

            $this->pricePermissions['getList'],
            $this->pricePermissions['getOne'],
            $this->pricePermissions['createOne'],
            $this->pricePermissions['updateOne'],
            $this->pricePermissions['deleteOne'],

            $this->requestPermissions['getList'],
            $this->requestPermissions['getOne'],

            $this->quotationPermissions['getList'],
            $this->quotationPermissions['getOne'],
            $this->quotationPermissions['createOne'],
            $this->quotationPermissions['updateOne'],
            $this->quotationPermissions['deleteOne'],

            $this->orderPermissions['getList'],
            $this->orderPermissions['getOne'],

            $this->stockPermissions['getList'],
            $this->stockPermissions['getOne'],
            $this->stockPermissions['createOne'],
            $this->stockPermissions['updateOne'],
            $this->stockPermissions['deleteOne'],

            $this->deliveryPermissions['getList'],
            $this->deliveryPermissions['getOne'],

        ], self::SUPPLIER_ROLE_NAME . ' role');
    }

    /**
     * Action adds Admin role with specific permissions
     *
     */
    public function actionAddCustomerRole()
    {
        $this->customerRole = $this->createRole(self::CUSTOMER_ROLE_NAME, [

            $this->productPermissions['getList'],
            $this->productPermissions['getOne'],

            $this->pricePermissions['getList'],
            $this->pricePermissions['getOne'],

            $this->requestPermissions['getList'],
            $this->requestPermissions['getOne'],
            $this->requestPermissions['createOne'],
            $this->requestPermissions['updateOne'],
            $this->requestPermissions['deleteOne'],

            $this->quotationPermissions['getList'],
            $this->quotationPermissions['getOne'],

            $this->orderPermissions['getList'],
            $this->orderPermissions['getOne'],
            $this->orderPermissions['createOne'],
            $this->orderPermissions['updateOne'],
            $this->orderPermissions['deleteOne'],

            $this->stockPermissions['getList'],
            $this->stockPermissions['getOne'],

        ], self::CUSTOMER_ROLE_NAME . ' role');
    }

    public function addBaseUser()
    {
        $this->stdout("Adding base user\n");
        $user = User::find()->where(['id' => '1']);
        if ($user->exists()) {
            $this->stdout("Base user is already exists\n");
            return $user->one();
        }
        $this->stdout("Creating new user\n");
        $user = new User();
        $user->username = self::BASE_USER_USERNAME;
        $user->setPassword(self::BASE_USER_PASSWORD);
        //$user->password_hash = Yii::$app->getSecurity()->generatePasswordHash(self::BASE_USER_PASSWORD);
        $user->generateAuthKey();
        $user->email = Yii::$app->params['adminEmail'];
        if ($user->save()) {
            $this->stdout("Base user has been created\n");
            return $user;
        }
        $this->stdout("Error creating base user\n");
        return false;
    }

    private function createPermissions($name)
    {
        $name = (string)$name;
        $getList = $this->createPermission('get'.ucfirst($name).'List', 'Get '.ucfirst($name).' list permission');
        $getOne = $this->createPermission('get'.ucfirst($name).'Details', 'Get '.ucfirst($name).' details permission');
        $createOne = $this->createPermission('create'.ucfirst($name).'Details', 'Create '.ucfirst($name).' details permission');
        $updateOne = $this->createPermission('update'.ucfirst($name).'Details', 'Update '.ucfirst($name).' details permission');
        $deleteOne = $this->createPermission('delete'.ucfirst($name).'Details', 'Delete '.ucfirst($name).' details permission');

        return [
            'getList'   => $getList,
            'getOne'    => $getOne,
            'createOne' => $createOne,
            'updateOne' => $updateOne,
            'deleteOne' => $deleteOne,
        ];
    }

    /**
     * Method creates new Permission
     *
     * @param $permissionName
     * @param string $permissionDesc
     * @return Permission
     */
    private function createPermission($permissionName, $permissionDesc = '')
    {
        $auth = Yii::$app->authManager;

        if (!$permission = $auth->getPermission($permissionName)) {
            $permissionDesc = empty($permissionDesc) ? $permissionName : $permissionDesc;

            $permission = $auth->createPermission($permissionName);
            $permission->description = $permissionDesc;
            if ($auth->add($permission)) {
                $this->stdout($permission->description . " has been added\n");
            }
        }
        return $permission;
    }

    /**
     * Method creates new Role with the specified $permissions
     *
     * @param $roleName
     * @param array $permissions
     * @param string $roleDesc
     * @return Role
     */
    private function createRole($roleName, array $permissions, $roleDesc = '')
    {
        $auth = Yii::$app->authManager;

        if ($role = $auth->getRole($roleName)) {
            $this->addRolePermissions($role, $permissions);
        } else {
            $roleDesc = empty($roleDesc) ? $roleName : $roleDesc;

            $role = $auth->createRole($roleName);
            $role->description = $roleDesc;
            if ($auth->add($role)) {
                $this->stdout($role->description . " has been added\n");
                $this->addRolePermissions($role, $permissions);
            }
        }
        return $role;
    }

    /**
     * Method adds specified $permissions to the specified $role
     *
     * @param Role $role
     * @param array $permissions
     */
    private function addRolePermissions(Role $role, array $permissions)
    {
        $auth = Yii::$app->authManager;

        foreach ($permissions as $permission) {
            if (!$this->checkRolePermission($role, $permission)) {
                $auth->addChild($role, $permission);
            }
        }
    }

    /**
     * Method adds specified child to the specified $role
     *
     * @param Role $role
     * @param $child
     */
    private function addRoleChild(Role $role, $child)
    {
        $auth = Yii::$app->authManager;

        if (!$this->checkRoleChild($role, $child)) {
            $auth->addChild($role, $child);
        }
    }

    /**
     * Method check whether specified $role has specified $permissions
     *
     * @param Role $role
     * @param Permission $permission
     * @return bool
     */
    private function checkRolePermission(Role $role, Permission $permission)
    {
        $auth = Yii::$app->authManager;

        $rolePermissions = $auth->getPermissionsByRole($role->name);

        return in_array($permission, $rolePermissions);
    }

    /**
     * Method check whether specified $role has specified $child
     *
     * @param Role $role
     * @param $child
     * @return bool
     */
    private function checkRoleChild(Role $role, $child)
    {
        $auth = Yii::$app->authManager;

        $roleChildren = $auth->getChildren($role->name);

        return in_array($child, $roleChildren);
    }

    private function assignRole(User $user, Role $role)
    {
        $auth = Yii::$app->authManager;

        if ($auth->assign($role, $user->id)) {
            $this->stdout($role->description . ' has been assigned to ' . $user->username);
            return true;
        }
        $this->stdout('Error assigning ' . $role->description . ' to ' . $user->username);
        return false;
    }

    /**
     * Method removes all Roles and Permissions
     */
    public function actionRemoveAll()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        $this->stdout("All permissions and roles have been removed\n");
    }
}