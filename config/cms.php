<?php

use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Feature;
use App\Models\HowItWorksStep;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Stat;
use App\Models\TeamMember;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| CMS permissions
|--------------------------------------------------------------------------
|
| Single source of truth for role permissions. Each resource generates
| permissions named "{ability} {key}" (e.g. "update campaigns"). To protect a
| new resource, add one entry here, add a policy extending ResourcePolicy, and
| re-run the RolesSeeder.
|
|   label     Heading used in the role editor.
|   model     Eloquent model the permissions apply to.
|   abilities Every permission generated for the resource.
|   editor    Abilities granted to the "editor" role (super-admin gets all).
|
*/
$crud = ['view', 'create', 'update', 'delete'];
$sortable = [...$crud, 'reorder'];

return [

    'resources' => [
        'campaigns' => ['label' => 'Campaigns', 'model' => Campaign::class, 'abilities' => $sortable, 'editor' => $sortable],
        'stats' => ['label' => 'Stats', 'model' => Stat::class, 'abilities' => $sortable, 'editor' => $sortable],
        'team members' => ['label' => 'Team members', 'model' => TeamMember::class, 'abilities' => $sortable, 'editor' => $sortable],
        'partners' => ['label' => 'Partners', 'model' => Partner::class, 'abilities' => $sortable, 'editor' => $sortable],
        'features' => ['label' => 'Features', 'model' => Feature::class, 'abilities' => $sortable, 'editor' => $sortable],
        'how-it-works steps' => ['label' => 'How it works steps', 'model' => HowItWorksStep::class, 'abilities' => $sortable, 'editor' => $sortable],
        // Pages are a fixed set of slugs: editors may only view and edit them.
        'pages' => ['label' => 'Pages', 'model' => Page::class, 'abilities' => $crud, 'editor' => ['view', 'update']],
        'contact messages' => ['label' => 'Contact messages', 'model' => ContactMessage::class, 'abilities' => ['view', 'update', 'delete'], 'editor' => ['view', 'update', 'delete']],
        'users' => ['label' => 'Users', 'model' => User::class, 'abilities' => $crud, 'editor' => []],
    ],

    // Standalone permissions: key => label. Editors do not receive these.
    'permissions' => [
        'manage site settings' => 'Manage site settings',
        'manage users' => 'Assign roles to users',
        'manage roles' => 'Manage roles and permissions',
    ],

    'roles' => [
        'super-admin' => 'super-admin',
        'editor' => 'editor',
    ],

];
