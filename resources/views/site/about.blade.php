
    @extends("layouts.site")

    @section("content")

    <section class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="display-4 fw-bold text-white mb-4">About Asiri</h1>
                <p class="text-muted lead">The Future of Honest Communication</p>
            </div>
        </div>
    </section>

    <!-- About Content -->
    <section class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-5"
                    style="background: rgba(255, 255, 255, 0.05); border-radius: 15px; border: 1px solid var(--glass-border); backdrop-filter: blur(10px);">

                    <h3 class="text-white mb-3">Our Mission</h3>
                    <p class="text-muted mb-4">
                        In a world of curated feeds and polished personas, it's hard to know what people really think.
                        Asiri (meaning "Secret" in many cultures) was built to break down these barriers. We believe
                        that anonymity, when used safely, can lead to the most honest and impactful conversations.
                    </p>

                    <h3 class="text-white mb-3">Why We Built This</h3>
                    <p class="text-muted mb-4">
                        We wanted to create a safe space for feedback, confessions, and fun. Whether you're looking for
                        honest advice from friends, constructive criticism from coworkers, or just want to interact with
                        your audience in a new way, Asiri provides the platform to do so without judgement.
                    </p>

                    <h3 class="text-white mb-3">Safety First</h3>
                    <p class="text-muted mb-4">
                        With anonymity comes responsibility. We utilize advanced moderation tools and allow users to
                        control their experience. Our commitment is to keeping Asiri a toxicity-free environment where
                        fun and safety coexist.
                    </p>

                    <div class="text-center mt-5">
                        <a href="/register" class="btn btn-premium px-5 py-3">Join the Conversation</a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @endsection
   