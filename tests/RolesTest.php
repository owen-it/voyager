<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TCG\Voyager\Models\Role;

class RolesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testRoles()
    {
        $this->visit(route('voyager.login'));
        $this->type('admin@admin.com', 'email');
        $this->type('password', 'password');
        $this->press('Login Logging in');
        $this->seePageIs(route('voyager.dashboard'));


        // Adding a New Role
        $this->visit(route('roles.index'))->click('Add New')->seePageIs(route('roles.create'));
        $this->type('superadmin', 'name');
        $this->type('Super Admin', 'display_name');
        $this->press('Submit');
        $this->seePageIs(route('roles.index'));
        $this->seeInDatabase('roles', ['name' => 'superadmin']);

        // Editing a Role
        $this->visit(route('roles.edit', 2));
        $this->type('regular_user', 'name');
        $this->press('Submit');
        $this->seePageIs(route('roles.index'));
        $this->seeInDatabase('roles', ['name' => 'regular_user']);

	    // Editing a Role
        $this->visit(route('roles.edit', 2));
        $this->type('user', 'name');
        $this->press('Submit');
        $this->seePageIs(route('roles.index'));
        $this->seeInDatabase('roles', ['name' => 'user']);

        // Get the current super admin role
        $superadmin_role = Role::where('name', '=', 'superadmin')->first();

	    // Deleting a Role
	    $response = $this->call('DELETE', route('roles.destroy', $superadmin_role->id), ['_token' => csrf_token()]);
	    $this->assertEquals(302, $response->getStatusCode());
        $this->notSeeInDatabase('roles', ['name' => 'superadmin']);
        
    }

}
