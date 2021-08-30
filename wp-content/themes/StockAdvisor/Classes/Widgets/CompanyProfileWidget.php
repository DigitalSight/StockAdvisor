<?php

include_once dirname( __DIR__, 1 ) . '/Clients/FMPClient.php';

class Company_Profile_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'company-profile-widget',
            'Company Profile Widget',
            [
                'description' => 'Will display the posts attached Stock taxonomy terms Company Profile information.'
            ]
        );

        add_action( 'widgets_init', function() {
            register_widget( 'Company_Profile_Widget' );
        });
    }

    public function widget($args, $instance)
    {
       //dependency check to determine if ACF is present
        if (class_exists('ACF')) {
            $postID = get_the_ID();
            $tickers = get_the_terms($postID, 'stocks');

            echo $args['before_widget'];

            foreach ($tickers as $ticker) {

                $symbol = get_field('symbol', $ticker->taxonomy . '_' . $ticker->term_id);
                if($symbol) {
                    $client = new FMPClient();
                    $companyData = $client->getCompanyProfile($symbol);
                    if(!is_wp_error($companyData) AND count($companyData) > 0) {
                        $data = reset($companyData);
                        ?>
                            <table class="profile-widget-table">
                                <th colspan="2" class="center-content">
                                    <image src="<?php echo $data["image"] ?>"></image>
                                </th>
                                <tr>
                                    <td class="bold-title">Company Name</td>
                                    <td><?php echo $data["companyName"] ?></td>
                                </tr>
                                <tr>
                                    <td class="bold-title">Exchange</td>
                                    <td><?php echo $data["exchangeShortName"] ?></td>
                                </tr>
                                <tr>
                                    <td class="bold-title center-content" colspan="2">Description</td>
                                </tr>
                                <tr>
                                    <td colspan="2" ><?php echo $data["description"] ?></td>
                                </tr>
                                <tr>
                                    <td class="bold-title">Industry</td>
                                    <td><?php echo $data["industry"] ?></td>
                                </tr>
                                <tr>
                                    <td class="bold-title">Sector</td>
                                    <td><?php echo $data["sector"] ?></td>
                                </tr>
                                <tr>
                                    <td class="bold-title">CEO</td>
                                    <td><?php echo $data["ceo"] ?></td>
                                </tr>
                                <tr>
                                    <td class="bold-title">Website</td>
                                    <td><a href="<?php echo $data["website"] ?>"><?php echo $data["website"] ?></a></td>
                                </tr>
                            </table>
                        <?php
                    }
                }
            }

            echo $args['after_widget'];
        }
    }
}
