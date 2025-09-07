<?php
/* 
* Template: Review Form
* Developer: Ariful
*/
?>
<?php if ( have_comments() ) : ?>
    
    <div class="rbfw-review-heading">
       <?php comments_number(rbfw_string_return('rbfw_text_no_review_yet',__('No Review Yet','rbfw-pro')), rbfw_string_return('rbfw_text_one_review',__('1 Review','rbfw-pro')), '% '. rbfw_string_return('rbfw_text_reviews',__('Reviews','rbfw-pro')) );?>
    </div>
    <ul class="rbfw-review-list">
       <?php wp_list_comments( array( 'callback' => 'rbfw_comment' ) ); ?>
    </ul>
    
    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ):?>  
        <div class="rbfw-review-pagination-wrap">
            <ul class="rbfw-review-pagination-list">
                <li><?php esc_url( previous_comments_link( '<i class="fa fa-angle-left"></i>' ) ) ?></li>
                <li><?php esc_url( next_comments_link( '<i class="fa fa-angle-right"></i>', 0 ) ) ?></li>
            </ul>
        </div>
    <?php endif; ?>

<?php endif; // end have_comments() ?>

<?php if ( ! comments_open() ) : ?>
        <div class="rbfw-review-heading"><?php rbfw_string('rbfw_text_reviews_are_closed',__('Reviews are closed','rbfw-pro')); ?></div>
<?php endif; ?>
    
<?php if ( comments_open() ) : ?>
    <?php
    $comment_count = get_comments_number();
    if($comment_count > 0):
        $class = 'rbfw-mt-50';
    else:
        $class = '';
    endif;      
    ?>
    <?php comment_form(array(
        'title_reply' => rbfw_string_return('rbfw_text_write_a_review',__('Write a Review','rbfw-pro')),
        'title_reply_before' => '<div class="rbfw-review-heading '.$class.'">',
        'title_reply_after' => '</div>',
        'comment_notes_before' => '<div class="rbfw-review-notes">' . rbfw_string_return('rbfw_text_your_email_will_not_be_published',__('Your email address will not be published.','rbfw-pro')) . rbfw_string_return('rbfw_text_required_fields_are_marked',__('Required Fields are Marked','rbfw-pro')) . ' <span class="required">*</span></div>',
        )); ?>		
    <!--#respond end-->
<?php endif; ?>
    