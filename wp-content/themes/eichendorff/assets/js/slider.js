( function () {
	const slider = document.querySelector( '.home-slider' );
	const slides = slider ? [ ...slider.querySelectorAll( ':scope > .wp-block-cover' ) ] : [];

	if ( slides.length < 2 ) {
		return;
	}

	const labels = Object.assign( { previous: 'Poprzedni slajd', next: 'Następny slajd', slide: 'Slajd %d' }, window.eichendorffSlider );
	const INTERVAL = 5000;
	const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	let current = 0;
	let timer = null;
	let stopped = reducedMotion;

	const createButton = ( className, label ) => {
		const button = document.createElement( 'button' );
		button.type = 'button';
		button.className = className;
		button.setAttribute( 'aria-label', label );
		return button;
	};

	const dots = document.createElement( 'div' );
	dots.className = 'home-slider__dots';

	const dotButtons = slides.map( ( slide, index ) => {
		const dot = createButton( 'home-slider__dot', labels.slide.replace( '%d', index + 1 ) );
		dots.append( dot );
		return dot;
	} );

	const show = ( index, direction ) => {
		const next = ( index + slides.length ) % slides.length;

		if ( next === current ) {
			return;
		}

		const incoming = slides[ next ];
		const outgoing = slides[ current ];

		incoming.style.transition = 'none';
		incoming.style.transform = `translateX(${ direction * 100 }%)`;
		incoming.classList.add( 'is-active' );
		// Applies the start position before the transition runs.
		incoming.getBoundingClientRect();
		incoming.style.transition = '';
		incoming.style.transform = 'translateX(0)';
		outgoing.style.transform = `translateX(${ direction * -100 }%)`;
		outgoing.classList.remove( 'is-active' );

		dotButtons[ current ].removeAttribute( 'aria-current' );
		dotButtons[ next ].setAttribute( 'aria-current', 'true' );
		current = next;
	};

	const stopTimer = () => {
		window.clearInterval( timer );
		timer = null;
	};

	const startTimer = () => {
		if ( ! stopped && ! timer ) {
			timer = window.setInterval( () => show( current + 1, 1 ), INTERVAL );
		}
	};

	const interact = ( index, direction ) => {
		stopped = true;
		stopTimer();
		show( index, direction );
	};

	const previous = createButton( 'home-slider__arrow is-previous', labels.previous );
	const next = createButton( 'home-slider__arrow is-next', labels.next );

	previous.addEventListener( 'click', () => interact( current - 1, -1 ) );
	next.addEventListener( 'click', () => interact( current + 1, 1 ) );
	dotButtons.forEach( ( dot, index ) => {
		dot.addEventListener( 'click', () => interact( index, index > current ? 1 : -1 ) );
	} );

	slider.append( previous, next, dots );
	slider.addEventListener( 'mouseenter', stopTimer );
	slider.addEventListener( 'mouseleave', startTimer );
	slider.addEventListener( 'focusin', stopTimer );
	slider.addEventListener( 'focusout', startTimer );

	slides[ 0 ].classList.add( 'is-active' );
	dotButtons[ 0 ].setAttribute( 'aria-current', 'true' );
	slider.classList.add( 'is-ready' );
	startTimer();
}() );
