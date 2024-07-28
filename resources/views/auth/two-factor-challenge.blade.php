<x-app-layout title="2fa challenge">
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600" x-show="! recovery">
                {{ __('Vui lòng xác nhận quyền truy cập vào tài khoản của bạn bằng cách nhập mã xác thực được cung cấp bởi ứng dụng xác thực của bạn.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600" x-cloak x-show="recovery">
                {{ __('Vui lòng xác nhận quyền truy cập vào tài khoản của bạn bằng cách nhập một trong các mã khôi phục khẩn cấp của bạn.') }}
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-label for="code" value="{{ __('Mã số') }}" />
                    <x-input id="code" class="block w-full mt-1" type="text" inputmode="numeric" name="code"
                        autofocus x-ref="code" autocomplete="one-time-code" />
                </div>

                <div class="mt-4" x-cloak x-show="recovery">
                    <x-label for="recovery_code" value="{{ __('Mã khôi phục') }}" />
                    <x-input id="recovery_code" class="block w-full mt-1" type="text" name="recovery_code"
                        x-ref="recovery_code" autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-gray-600 underline cursor-pointer hover:text-gray-900"
                        x-show="! recovery"
                        x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                        {{ __('Sử dụng mã khôi phục') }}
                    </button>

                    <button type="button" class="text-sm text-gray-600 underline cursor-pointer hover:text-gray-900"
                        x-cloak x-show="recovery"
                        x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                        {{ __('Sử dụng mã xác thực') }}
                    </button>

                    <x-button class="ml-4">
                        {{ __('Đăng nhập') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-app-layout>
