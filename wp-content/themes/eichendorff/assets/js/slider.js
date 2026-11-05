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

	// Dragging with the mouse or a finger: the slide follows the pointer and the neighbour slides in beside it.
	const DRAG_START = 8;
	const DRAG_COMMIT = 0.2;
	const FLICK_SPEED = 0.5;

	let drag = null;
	let suppressClick = false;

	const setPosition = ( slide, transform, animate ) => {
		slide.style.transition = animate ? '' : 'none';
		slide.style.transform = transform;
	};

	const neighbourFor = ( dx ) => {
		const direction = dx < 0 ? 1 : -1;
		return { direction, index: ( current + direction + slides.length ) % slides.length };
	};

	const endDrag = ( event ) => {
		if ( ! drag || event.pointerId !== drag.pointerId ) {
			return;
		}

		const { dx, neighbour, dragging } = drag;
		drag = null;
		slider.classList.remove( 'is-dragging' );

		if ( ! dragging || ! neighbour ) {
			return;
		}

		// Swallows the click that follows the drag, but only that one.
		suppressClick = true;
		window.setTimeout( () => {
			suppressClick = false;
		}, 0 );
		const elapsed = Math.max( 1, event.timeStamp - neighbour.startTime );
		const commit = Math.abs( dx ) > slider.offsetWidth * DRAG_COMMIT || ( Math.abs( dx ) > 30 && Math.abs( dx ) / elapsed > FLICK_SPEED );
		const outgoing = slides[ current ];
		const incoming = slides[ neighbour.index ];

		if ( ! commit ) {
			setPosition( outgoing, 'translateX(0)', true );
			setPosition( incoming, `translateX(${ neighbour.direction * 100 }%)`, true );

			if ( ! slider.matches( ':hover' ) ) {
				startTimer();
			}
			return;
		}

		stopped = true;
		stopTimer();
		incoming.classList.add( 'is-active' );
		setPosition( incoming, 'translateX(0)', true );
		setPosition( outgoing, `translateX(${ neighbour.direction * -100 }%)`, true );
		outgoing.classList.remove( 'is-active' );
		dotButtons[ current ].removeAttribute( 'aria-current' );
		dotButtons[ neighbour.index ].setAttribute( 'aria-current', 'true' );
		current = neighbour.index;
	};

	slider.addEventListener( 'pointerdown', ( event ) => {
		if ( drag || ( 'mouse' === event.pointerType && 0 !== event.button ) || event.target.closest( 'button' ) ) {
			return;
		}

		drag = { pointerId: event.pointerId, startX: event.clientX, startY: event.clientY, dx: 0, dragging: false, neighbour: null };
	} );

	slider.addEventListener( 'pointermove', ( event ) => {
		if ( ! drag || event.pointerId !== drag.pointerId ) {
			return;
		}

		const dx = event.clientX - drag.startX;
		const dy = event.clientY - drag.startY;

		if ( ! drag.dragging ) {
			if ( Math.abs( dy ) > DRAG_START && Math.abs( dy ) > Math.abs( dx ) ) {
				// A vertical gesture scrolls the page.
				drag = null;
				return;
			}

			if ( Math.abs( dx ) < DRAG_START ) {
				return;
			}

			drag.dragging = true;
			try {
				slider.setPointerCapture( event.pointerId );
			} catch {
				// Without capture the drag still works while the pointer stays over the slider.
			}
			slider.classList.add( 'is-dragging' );
			stopTimer();
		}

		const width = slider.offsetWidth;
		const neighbour = neighbourFor( dx );

		if ( ! drag.neighbour || drag.neighbour.index !== neighbour.index ) {
			if ( drag.neighbour ) {
				setPosition( slides[ drag.neighbour.index ], `translateX(${ drag.neighbour.direction * 100 }%)`, false );
			}

			drag.neighbour = { ...neighbour, startTime: event.timeStamp };
		}

		drag.dx = Math.max( -width, Math.min( width, dx ) );
		setPosition( slides[ current ], `translateX(${ drag.dx }px)`, false );
		setPosition( slides[ neighbour.index ], `translateX(calc(${ neighbour.direction * 100 }% + ${ drag.dx }px))`, false );
	} );

	slider.addEventListener( 'pointerup', endDrag );
	slider.addEventListener( 'pointercancel', endDrag );
	slider.addEventListener( 'dragstart', ( event ) => event.preventDefault() );
	slider.addEventListener(
		'click',
		( event ) => {
			if ( suppressClick ) {
				suppressClick = false;
				event.preventDefault();
				event.stopPropagation();
			}
		},
		true
	);

	// When every slide has the same text, it is shown once above the slides, so only the photos move
	// and the text does not slide in again with each slide. Slides with different texts keep their own.
	const texts = slides.map( ( slide ) => slide.querySelector( '.wp-block-cover__inner-container' ) );
	const sharedText = texts.every( ( text ) => text && text.textContent.trim() === texts[ 0 ].textContent.trim() );

	if ( sharedText ) {
		const content = document.createElement( 'div' );
		content.className = 'home-slider__content';
		content.append( texts[ 0 ] );
		texts.slice( 1 ).forEach( ( text ) => text.remove() );
		slider.append( content );
	}

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
