<link rel="stylesheet" href="{{ asset('front/css/toast.css') }}">
<script src="{{ asset('front/js/toast.js') }}"></script>
<script>
    @foreach (['success', 'error', 'warning', 'info'] as $toastType)
        @if (session()->has($toastType))
            window.SuperFitToast.show(@json(session($toastType)), @json($toastType));
        @endif
    @endforeach
    @if ($errors->any())
        window.SuperFitToast.error(@json($errors->first()));
    @endif
</script>
