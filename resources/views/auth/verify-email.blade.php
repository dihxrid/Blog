<x-app-layout title="Verify Email">
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Trước khi tiếp tục, bạn có thể xác minh địa chỉ email của mình bằng cách nhấp vào liên kết mà chúng tôi vừa gửi qua email cho bạn không? Nếu bạn không nhận được email, chúng tôi sẽ sẵn lòng gửi cho bạn một email khác.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn đã cung cấp trong cài đặt hồ sơ của mình.') }}
            </div>
        @endif

        <div class="flex items-center justify-between mt-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        {{ __('Gửi lại email xác minh') }}
                    </x-button>
                </div>
            </form>

            <div>
                <a href="{{ route('profile.show') }}"
                    class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Chỉnh sửa thông tin') }}</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit"
                        class="ml-2 text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Đăng xuất') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-app-layout>
