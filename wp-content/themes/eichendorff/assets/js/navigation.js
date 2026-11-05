// The Navigation block expands every submenu inside its overlay; the mobile panel uses an accordion.
( function () {
	const overlay = document.querySelector( '.site-nav .wp-block-navigation__responsive-container' );

	if ( ! overlay ) {
		return;
	}

	const toggles = overlay.querySelectorAll( '.wp-block-navigation-submenu__toggle' );

	const collapseAll = () => {
		toggles.forEach( ( toggle ) => {
			toggle.parentElement.classList.remove( 'is-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		} );
	};

	toggles.forEach( ( toggle ) => {
		toggle.addEventListener( 'click', () => {
			if ( ! overlay.classList.contains( 'is-menu-open' ) ) {
				return;
			}
			const isOpen = toggle.parentElement.classList.toggle( 'is-open' );
			toggle.setAttribute( 'aria-expanded', String( isOpen ) );
		} );
	} );

	document.addEventListener(
		'keydown',
		( event ) => {
			if ( 'Escape' === event.key && overlay.classList.contains( 'is-menu-open' ) ) {
				event.stopPropagation();
				overlay.querySelector( '.wp-block-navigation__responsive-container-close' ).click();
				requestAnimationFrame( () => {
					overlay.parentElement.querySelector( '.wp-block-navigation__responsive-container-open' ).focus();
				} );
			}
		},
		true
	);

	new MutationObserver( () => {
		if ( overlay.classList.contains( 'is-menu-open' ) ) {
			requestAnimationFrame( collapseAll );
		}
	} ).observe( overlay, { attributes: true, attributeFilter: [ 'class' ] } );
}() );
