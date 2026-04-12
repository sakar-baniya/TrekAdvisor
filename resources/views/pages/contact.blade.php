<x-app-layout>
    <style>
        .contact-shell {
            display: grid;
            gap: 2rem;
            grid-template-columns: minmax(0, 1fr) minmax(0, 0.7fr);
        }

        .contact-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        }

        .contact-alert {
            background: #ecfdf3;
            border: 1px solid #b7efc5;
            color: #0f5132;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .contact-alert--error {
            background: #fff4f4;
            border: 1px solid #f7c6c6;
            color: #9b1c1c;
        }

        .contact-form {
            display: grid;
            gap: 1rem;
        }

        .contact-form label {
            display: grid;
            gap: 0.5rem;
            font-weight: 600;
        }

        .contact-form .market-input {
            width: 100%;
        }

        .contact-meta {
            display: grid;
            gap: 0.75rem;
        }

        .contact-meta .detail-review-card {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        @media (max-width: 960px) {
            .contact-shell {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="catalog-hero">
        <div class="container">
            <p class="market-kicker">Contact</p>
            <h1>We would love to hear from you</h1>
            <p>Reach out for booking support, trek planning questions, or partnership conversations.</p>
        </div>
    </section>

    <section class="market-section">
        <div class="container contact-shell">
            <section class="detail-main">
                <article class="contact-card">
                    <h2>Send a Message</h2>
                    <p>Share a few details and our support team will reply within one business day.</p>

                    @if (session('success'))
                        <div class="contact-alert">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="contact-alert contact-alert--error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form class="contact-form" method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <label>
                            <span>Full Name</span>
                            <input class="market-input" name="name" value="{{ old('name') }}" placeholder="Your full name" required>
                        </label>
                        <label>
                            <span>Email</span>
                            <input class="market-input" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                        </label>
                        <label>
                            <span>Subject</span>
                            <select class="market-input" name="subject" required>
                                <option value="">Select a topic</option>
                                <option value="General Inquiry" @selected(old('subject') === 'General Inquiry')>General Inquiry</option>
                                <option value="Booking Support" @selected(old('subject') === 'Booking Support')>Booking Support</option>
                                <option value="Trek Question" @selected(old('subject') === 'Trek Question')>Trek Question</option>
                            </select>
                        </label>
                        <label>
                            <span>Message</span>
                            <textarea class="market-input" name="message" rows="6" placeholder="Tell us how we can help" required>{{ old('message') }}</textarea>
                        </label>
                        <button type="submit" class="market-button">Send Message</button>
                    </form>
                </article>
            </section>

            <aside class="detail-sidebar">
                <div class="contact-card contact-meta">
                    <h3>Contact Details</h3>
                    <div class="detail-review-card">
                        <strong>Kathmandu, Nepal</strong>
                        <span>Tourism support office</span>
                    </div>
                    <div class="detail-review-card">
                        <strong>hello@trekadvisor.com</strong>
                        <span>Email support</span>
                    </div>
                    <div class="detail-review-card">
                        <strong>+977 9800000000</strong>
                        <span>Mon-Fri, 9AM-6PM</span>
                    </div>
                    <div class="detail-review-card">
                        <strong>Response time</strong>
                        <span>Within one business day</span>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-app-layout>
