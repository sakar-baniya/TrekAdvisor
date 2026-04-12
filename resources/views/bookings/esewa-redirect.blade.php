<x-app-layout>
    <div class="container booking-wrap">
        <div class="booking-layout-card">
            <div class="booking-step-card booking-step-card--wide" style="text-align: center;">
                <h1>Redirecting to eSewa</h1>
                <p class="booking-subtext">Please wait while we connect you to eSewa checkout.</p>

                <form id="esewaCheckoutForm" action="{{ $endpoint }}" method="POST">
                    @foreach($payload as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

                <p class="booking-subtext" style="margin-top: 12px;">If you are not redirected, click below.</p>
                <button type="submit" form="esewaCheckoutForm" class="market-search-btn">Continue to eSewa</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('esewaCheckoutForm').submit();
        });
    </script>
</x-app-layout>
