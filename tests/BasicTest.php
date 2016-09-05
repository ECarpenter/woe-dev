<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;

use App\User;
use App\Property;
use App\group;
use App\tenant;

class BasicTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Welcome');
    }

    public function testGetTenentRegister()
    {
        $this->visit('/tenantregister');
    }

    public function testPropertyList()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('property/list')
            ->see('testPropertyList');
    }

    public function testHome()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('home')
            ->see('testHome');
    }

    public function testSubmit()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('submit')
            ->see('testSubmit')
            ->select(1,'tenant')
            ->select(2,'type')
            ->type('Shenanigans!','description')
            ->press('Submit')
            ->seePageIs('/home');
    }

    public function testUserChangePassword()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('user/changepassword')
            ->see('testuser/changepassword');
    }

    public function testPropertyAdd()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $property_id = '';

        While($property_id == '')
        {
            $property_id = Str::quickRandom(10);
            if (Property::where('group_system_id', '=', $property_id)->first() != null)
            {
                $property_id = '';
            }
        }

        $this->actingAs($admin)
            ->visit('property/add')
            ->see('testPropertyAdd')
            ->select(1,'owner_id')
            ->select(13,'manager')
            ->type('Troll Knoll','name')
            ->type($property_id,'property_system_id')
            ->type('6340 Wilshire Blvd','address')
            ->type('Los Angeles','city')
            ->type('CA','state')
            ->type('90392','zip')
            ->type('1000000','req_liability_single_limit')
            ->type('2000000','req_liability_combined_limit')
            ->type('500000','req_auto_limit')
            ->type('500000','req_umbrella_limit')
            ->type('500000','req_workerscomp_limit')
            ->press('Submit');

        $property = Property::where('property_system_id', '=', $property_id)->first();

        $this->actingAs($admin)
            ->seePageIs('/property/'.$property->id)
            ->see('testProperty1');
    }

     public function testProperty1()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('property/1')
            ->see('testProperty1');
    }

     public function testGroupList()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('group/list')
            ->see('testGroupList');
    }

     public function testGroupAdd()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

      $group_id = '';

        While($group_id == '')
        {
            $group_id = Str::quickRandom(10);
            if (Group::where('group_system_id', '=', $group_id)->first() != null)
            {
                $group_id = '';
            }
        }
     
        $this->actingAs($admin)
            ->visit('group/add')
            ->see('testGroupAdd')
            ->type('Swaggler','name')
            ->type($group_id,'group_system_id')
            ->press('Submit');
    
        $group = Group::where('group_system_id', '=', $group_id)->first();

        $this->actingAs($admin)
            ->seePageIs('/group/'.$group->id.'/manage')
            ->see('testGroup1Manage');
     }

    public function testGroup1()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('group/1')
            ->see('testGroup1');
    }

     public function testGroup1Manage()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('group/1/manage')
            ->see('testGroup1Manage');
    }

     public function testTenantList()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('tenant/list')
            ->see('testTenantList');
    }

     public function testTenantUploadList()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('tenant/uploadlist')
            ->see('testTenantLists');
    }

     public function testTenantNonComplianceList()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('tenant/noncompliancelist')
            ->see('testTenantLists');
    }

     public function testTenantAdd()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $tenant_id = '';

        While($tenant_id == '')
        {
            $tenant_id = Str::quickRandom(10);
            if (Tenant::where('tenant_system_id', '=', $tenant_id)->first() != null)
            {
                $tenant_id = '';
            }
        }
     {
        $this->actingAs($admin)
            ->visit('tenant/add')
            ->see('testTenantAdd')
            ->select(1,'property')
            ->type($tenant_id,'tenant_system_id')
            ->type(23,'suite')
            ->type('Underhill Lighting','company_name')
            ->type('underhilllighting@gmail.com','email')
            ->press('Submit');
    
        $tenent = Tenant::where('tenant_system_id', '=', $tenant_id)->first();

     }
        $this->actingAs($admin)
            ->visit('tenant/add')
            ->see('testTenantAdd');
    }

     public function testTenantUnverifiedList()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('tenant/unverifiedlist')
            ->see('testTenantUnverifiedList');
    }

     public function testTenant1()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('tenant/1')
            ->see('testTenant1');
    }

    //  public function testUser1()
    // {
    //     $admin = User::where('email','=','admin@ejcustom.com')->first();

    //     $this->actingAs($admin)
    //         ->visit('user/1')
    //         ->see('testUser1');
    // }
    
     public function testWorkOrders()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('workorders')
            ->see('testWorkOrders');
    }

     public function testWorkOrders1()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('workorders/1')
            ->see('testWorkOrders1');
    }

     public function testWorkOrders1Edit()
    {
        $admin = User::where('email','=','admin@ejcustom.com')->first();

        $this->actingAs($admin)
            ->visit('workorders/1/edit')
            ->see('testWorkOrders1Edit');
    }

}
