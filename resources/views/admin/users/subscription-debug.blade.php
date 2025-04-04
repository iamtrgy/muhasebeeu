<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Subscription Debug') }} - {{ $user->name }}"></x-admin.page-title>
    </x-slot>

    <div>
        <!-- User Info -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">User Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Name: <span class="text-gray-900 dark:text-white font-medium">{{ $user->name }}</span></p>
                        <p class="text-gray-600 dark:text-gray-400">Email: <span class="text-gray-900 dark:text-white font-medium">{{ $user->email }}</span></p>
                        <p class="text-gray-600 dark:text-gray-400">User ID: <span class="text-gray-900 dark:text-white font-medium">{{ $user->id }}</span></p>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Stripe Customer ID: <span class="text-gray-900 dark:text-white font-medium">{{ $user->stripe_id ?? 'Not created' }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Subscription Status -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Subscription Status</h2>
                
                @if(isset($subscriptionInfo['error']))
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4 mb-4">
                        <p class="text-red-600 dark:text-red-400">{{ $subscriptionInfo['error'] }}</p>
                    </div>
                    
                    <div class="flex space-x-2 mt-4">
                        <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="subscribe">
                            <input type="hidden" name="plan" value="pro">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Create Pro Plan Subscription
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Display subscription details -->
                    <div class="overflow-hidden">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <h3 class="text-md font-medium mb-2">Database Information</h3>
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <ul class="space-y-2">
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Subscription ID:</span>
                                            <span class="text-gray-900 dark:text-white font-mono">{{ $subscriptionInfo['id'] }}</span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Stripe Subscription ID:</span>
                                            <span class="text-gray-900 dark:text-white font-mono">{{ $subscriptionInfo['stripe_id'] }}</span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Stripe Price ID:</span>
                                            <span class="text-gray-900 dark:text-white font-mono">{{ $subscriptionInfo['stripe_price'] }}</span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Created:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $subscriptionInfo['created_at'] }}</span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Ends at:</span>
                                            <span class="text-gray-900 dark:text-white">{{ $subscriptionInfo['ends_at'] ?? 'Not set' }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="col-span-2 md:col-span-1">
                                <h3 class="text-md font-medium mb-2">Status Checks</h3>
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <ul class="space-y-2">
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Cashier subscribed():</span>
                                            <span class="font-medium {{ $subscriptionInfo['cashier_check'] ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $subscriptionInfo['cashier_check'] ? 'Active' : 'Inactive' }}
                                            </span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Manual DB check:</span>
                                            <span class="font-medium {{ $subscriptionInfo['manual_check'] ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $subscriptionInfo['manual_check'] ? 'Active' : 'Inactive' }}
                                            </span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">On trial:</span>
                                            <span class="font-medium {{ $subscriptionInfo['on_trial'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                                                {{ $subscriptionInfo['on_trial'] ? 'Yes' : 'No' }}
                                            </span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Canceled:</span>
                                            <span class="font-medium {{ $subscriptionInfo['canceled'] ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-600 dark:text-gray-400' }}">
                                                {{ $subscriptionInfo['canceled'] ? 'Yes' : 'No' }}
                                            </span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Grace period:</span>
                                            <span class="font-medium {{ $subscriptionInfo['on_grace_period'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400' }}">
                                                {{ $subscriptionInfo['on_grace_period'] ? 'Yes' : 'No' }}
                                            </span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Ended:</span>
                                            <span class="font-medium {{ $subscriptionInfo['ended'] ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400' }}">
                                                {{ $subscriptionInfo['ended'] ? 'Yes' : 'No' }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($subscriptionInfo['stripe_status']))
                        <h3 class="text-md font-medium my-4">Stripe Information</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <ul class="space-y-2">
                                <li class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Stripe Status:</span>
                                    <span class="font-medium {{ $subscriptionInfo['stripe_status'] === 'active' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                        {{ ucfirst($subscriptionInfo['stripe_status']) }}
                                    </span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Current Period End:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $subscriptionInfo['stripe_current_period_end'] }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Payment Methods:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $subscriptionInfo['payment_methods_count'] ?? 'Unknown' }}</span>
                                </li>
                            </ul>
                        </div>
                        @elseif(isset($subscriptionInfo['stripe_error']))
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mt-4">
                            <p class="text-yellow-600 dark:text-yellow-400">Stripe API Error: {{ $subscriptionInfo['stripe_error'] }}</p>
                        </div>
                        @endif
                        
                        <!-- New explanation box about subscription states -->
                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Understanding Subscription States</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mb-2">Here's a guide to subscription states and what they mean:</p>
                            <ul class="list-disc list-inside text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                <li><strong>Active</strong>: The subscription is active and will automatically renew.</li>
                                <li><strong>Trial</strong>: The subscription is in trial period and will convert to paid when the trial ends.</li>
                                <li><strong>Canceled (Grace Period)</strong>: The subscription has been marked for cancellation but access continues until the current billing period ends. <em>Can be resumed.</em></li>
                                <li><strong>Canceled (Ended)</strong>: The subscription has been fully canceled and the grace period has ended. <em>Cannot be resumed - must create new subscription.</em></li>
                                <li><strong>Stripe status "canceled"</strong>: The subscription has been immediately canceled in Stripe (not waiting for period end). <em>Cannot be resumed.</em></li>
                                <li><strong>Incomplete</strong>: Payment failed when creating subscription. Should be deleted and recreated.</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Quick fixes -->
                    <div class="mt-6">
                        <h3 class="text-md font-medium mb-2">Quick Fixes</h3>
                        <div class="flex flex-wrap gap-2">
                            <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="subscribe">
                                <input type="hidden" name="plan" value="pro">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    Recreate Pro Subscription
                                </button>
                            </form>
                            
                            @if($subscriptionInfo['canceled'] && !$subscriptionInfo['ended'])
                            <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="resume">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                    Resume Subscription
                                </button>
                            </form>
                            @elseif(!$subscriptionInfo['canceled'])
                            <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    Cancel Subscription
                                </button>
                            </form>
                            @endif
                            
                            @if(isset($subscriptionInfo['stripe_status']) && ($subscriptionInfo['stripe_status'] === 'incomplete' || $subscriptionInfo['stripe_status'] === 'incomplete_expired'))
                            <form action="{{ route('admin.users.subscription.update', $user) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="delete_incomplete">
                                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg">
                                    Delete Incomplete Subscription
                                </button>
                            </form>
                            @endif
                        </div>
                        
                        <!-- Additional subscription state info -->
                        <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-2">Subscription Actions Guide</h4>
                            <ul class="list-disc list-inside text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                                @if($subscriptionInfo['canceled'] && $subscriptionInfo['ended'])
                                    <li>This subscription has <strong>ended</strong> and cannot be resumed. You must create a new subscription.</li>
                                @elseif($subscriptionInfo['canceled'] && $subscriptionInfo['on_grace_period'])
                                    <li>This subscription is in <strong>grace period</strong> and can be resumed.</li>
                                @elseif(isset($subscriptionInfo['stripe_status']) && $subscriptionInfo['stripe_status'] === 'canceled')
                                    <li>This subscription has been <strong>fully canceled in Stripe</strong> and cannot be resumed. You must create a new subscription.</li>
                                @elseif(isset($subscriptionInfo['stripe_status']) && ($subscriptionInfo['stripe_status'] === 'incomplete' || $subscriptionInfo['stripe_status'] === 'incomplete_expired'))
                                    <li>This is an <strong>incomplete subscription</strong>. Payment failed when creating it. You should delete it and create a new one.</li>
                                @elseif(!$subscriptionInfo['canceled'])
                                    <li>This subscription is <strong>active</strong>. You can cancel it or change plans.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Recent Activity Logs -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Recent Subscription Activity</h2>
                
                @if(count($logs) > 0)
                <x-admin.table>
                    <x-slot name="header">
                        <x-admin.table.th>Action</x-admin.table.th>
                        <x-admin.table.th>Description</x-admin.table.th>
                        <x-admin.table.th>Date</x-admin.table.th>
                    </x-slot>
                    @foreach($logs as $log)
                        <x-admin.table.tr>
                            <x-admin.table.td class="font-medium text-gray-900 dark:text-white">
                                {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                            </x-admin.table.td>
                            <x-admin.table.td>
                                {{ $log->description }}
                                @if($log->properties)
                                <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                    @php
                                        $properties = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                                    @endphp
                                    @if(is_array($properties))
                                        @foreach($properties as $key => $value)
                                            @if($key !== 'admin_id')
                                                <span class="mr-1">{{ ucfirst($key) }}: <span class="font-semibold">{{ is_array($value) ? json_encode($value) : $value }}</span></span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                @endif
                            </x-admin.table.td>
                            <x-admin.table.td>
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @endforeach
                </x-admin.table>
                @else
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No subscription activity logs found.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout> 