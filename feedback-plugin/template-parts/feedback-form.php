 <?php 
    $plugin_file = plugin_dir_path(__DIR__) . 'feedback-plugin.php'; 
 ?>

 <article class="card" style="background-image:url(<?= esc_url( plugins_url( 'assets/photo-wall-texture-pattern.webp', $plugin_file ) ) ?>)">
        <picture class="card__image">
        <source srcset="<?= esc_url( plugins_url( 'assets/speaker-desktop.webp', $plugin_file ) ) ?>" media="(min-width: 57.5rem)">
        <img src="<?= esc_url( plugins_url( 'assets/speakers.webp', $plugin_file ) ) ?>" width="300" height="287.48" aria-hidden="true" alt="">
        </picture>
        <div class="card__content">    
          <form id="feedback-form" class="feedback" novalidate>
            <h2 class="feedback__title"><span>Feedback Form</span>
            Have your say <img src="data:image/svg+xml,%3csvg%20xmlns='http://www.w3.org/2000/svg'%20xmlns:xlink='http://www.w3.org/1999/xlink'%20width='6.577'%20height='5.696'%20viewBox='0%200%206.577%205.696'%3e%3cdefs%3e%3cradialGradient%20id='radial-gradient'%20cx='0.5'%20cy='0.5'%20r='0.5'%20gradientUnits='objectBoundingBox'%3e%3cstop%20offset='0'%20stop-color='%23f9d309'/%3e%3cstop%20offset='1'%20stop-color='%23fb0'/%3e%3c/radialGradient%3e%3c/defs%3e%3cpath%20id='hexagon-svgrepo-com_1_'%20data-name='hexagon-svgrepo-com%20(1)'%20d='M0,15.223l1.644-2.848H4.933l1.644,2.848L4.933,18.071H1.644Z'%20transform='translate(0%20-12.375)'%20fill='url(%23radial-gradient)'/%3e%3c/svg%3e" alt="" aria-hidden="true"></h2>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" id="email" class="feedback__input" name="email" placeholder="you@example.com" aria-describedby="email-error" required/>
              <span class="feedback__progress-indicator">
              <svg xmlns="http://www.w3.org/2000/svg" width="15.533" height="17.287" viewBox="0 0 15.533 17.287">
                <g transform="translate(-22.968 1.143)">
                  <path id="indicator" d="M37.5,11.25V3.75L30.734,0,23.968,3.75v7.5L30.734,15Z"
                    fill="none"
                    stroke="#F9D309"
                    stroke-width="2"
                    />
                </g>
              </svg>
              </span>
              <span id="email-error" class="feedback__error"></span>
            </div>
            <div class="form-group">
              <label for="message">Message:</label>
              <textarea id="message" class="feedback__input" name="message" placeholder="Write your message here.." aria-describedby="message-error" required></textarea>
              <span class="feedback__progress-indicator">
                <svg xmlns="http://www.w3.org/2000/svg" width="15.533" height="17.287" viewBox="0 0 15.533 17.287">
                <g transform="translate(-22.968 1.143)">
                  <path id="indicator" d="M37.5,11.25V3.75L30.734,0,23.968,3.75v7.5L30.734,15Z"
                    fill="none"
                    stroke="#F9D309"
                    stroke-width="2"
                    />
                </g>
              </svg>
              </span>
              <span id="message-error" class="feedback__error"></span>
            </div>
            <button type="submit" class="feedback__button" disabled>Send</button>
          </form>
        </div>
        <div id="success" class="feedback__success" aria-live="polite" aria-describedby="success__desc" aria-labelledby="success__title">
          <h2 id="success__title">Success!</h2>
          <p id="succss__desc">Feedback Received!</p>
        </div>
      </article>