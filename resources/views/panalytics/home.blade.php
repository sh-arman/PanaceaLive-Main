@extends('layouts.front_panalytics')

@section('content')
	<!-- Splash Screen -->
	<div id="home">
		<div id="particles-js" >
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center">
						@if(\Illuminate\Support\Facades\Session::get('company_user_session'))
							<a href="{{ route('panalytics_view') }}" class="btn" >Panalytics</a>
						@else
							<a href="{{ route('panalytics_home') }}" class="btn" >Panalytics</a>
						@endif

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.Splash Screen -->
@endsection

@section('scripts')
	@parent
	<script src="{{ asset('panalytics/js/main.js') }}"></script>
	<!-- <script src="{{ asset('frontend/js/particles.js') }}"></script>
	<script src="{{ asset('frontend/js/jquery.smoove.min.js') }}"></script>
	<script>
		particlesJS('particles-js', {
			particles: {
				color: '#fff',
				shape: 'circle', // "circle", "edge" or "triangle"
				opacity: 0.2,
				size: 4,
				size_random: true,
				nb: 150,
				line_linked: {
					enable_auto: true,
					distance: 100,
					color: '#fff',
					opacity: 1,
					width: 1,
					condensed_mode: {
						enable: false,
						rotateX: 600,
						rotateY: 600
					}
				},
				anim: {
					enable: true,
					speed: 1
				}
			},
			interactivity: {
				enable: true,
				mouse: {
					distance: 250
				},
				detect_on: 'canvas', // "canvas" or "window"
				mode: 'grab',
				line_linked: {
					opacity: .5
				},
				events: {
					onclick: {
						enable: false,
						mode: 'push', // "push" or "remove" (particles)
						nb: 4
					}
				}
			},
			/* Retina Display Support */
			retina_detect: true
		});
	</script>
	-->
@endsection
