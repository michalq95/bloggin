{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Comments" icon="la la-question" :link="backpack_url('comment')" />
<x-backpack::menu-item title="Contents" icon="la la-question" :link="backpack_url('content')" />
<x-backpack::menu-item title="Donations" icon="la la-question" :link="backpack_url('donation')" />
<x-backpack::menu-item title="Donation orders" icon="la la-question" :link="backpack_url('donation-order')" />
<x-backpack::menu-item title="Images" icon="la la-question" :link="backpack_url('image')" />
<x-backpack::menu-item title="Posts" icon="la la-question" :link="backpack_url('post')" />
<x-backpack::menu-item title="Premium memberships" icon="la la-question" :link="backpack_url('premium-membership')" />
<x-backpack::menu-item title="Tags" icon="la la-question" :link="backpack_url('tag')" />
<x-backpack::menu-item title="Taggables" icon="la la-question" :link="backpack_url('taggable')" />
<x-backpack::menu-item title="Uploads" icon="la la-question" :link="backpack_url('uploads')" />
<x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" />