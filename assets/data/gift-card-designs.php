<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
    'gradients' => [
        'sky' => 'radial-gradient(82% 100% at -4% -4%, rgba(144, 220, 255, 0.80) 0%, rgba(144, 220, 255, 0) 100%), radial-gradient(66% 84% at 104% 103%, rgba(79, 178, 255, 0.56) 0%, rgba(79, 178, 255, 0) 100%), radial-gradient(56% 68% at 50% 104%, rgba(190, 246, 255, 0.34) 0%, rgba(190, 246, 255, 0) 100%), linear-gradient(135deg, rgb(241, 249, 255) 0%, rgb(233, 245, 255) 100%)',

        'violet' => 'radial-gradient(82% 100% at -4% -4%, rgba(185, 143, 255, 0.80) 0%, rgba(185, 143, 255, 0) 100%), radial-gradient(66% 84% at 104% 103%, rgba(129, 92, 236, 0.58) 0%, rgba(129, 92, 236, 0) 100%), radial-gradient(56% 68% at 50% 104%, rgba(227, 190, 255, 0.34) 0%, rgba(227, 190, 255, 0) 100%), linear-gradient(135deg, rgb(247, 242, 255) 0%, rgb(239, 236, 252) 100%)',

        'fuchsia' => 'radial-gradient(82% 100% at -4% -4%, rgba(255, 104, 198, 0.82) 0%, rgba(255, 104, 198, 0) 100%), radial-gradient(66% 84% at 104% 103%, rgba(214, 58, 164, 0.58) 0%, rgba(214, 58, 164, 0) 100%), radial-gradient(56% 68% at 50% 104%, rgba(255, 175, 227, 0.34) 0%, rgba(255, 175, 227, 0) 100%), linear-gradient(135deg, rgb(255, 242, 249) 0%, rgb(252, 235, 245) 100%)',

        'lime_mint' => 'radial-gradient(82% 100% at -4% -4%, rgba(176, 244, 96, 0.84) 0%, rgba(176, 244, 96, 0) 100%), radial-gradient(66% 84% at 104% 103%, rgba(60, 206, 152, 0.62) 0%, rgba(60, 206, 152, 0) 100%), radial-gradient(56% 66% at 48% 104%, rgba(230, 247, 111, 0.36) 0%, rgba(230, 247, 111, 0) 100%), linear-gradient(135deg, rgb(245, 251, 237) 0%, rgb(234, 249, 239) 100%)',

        'lime_peach' => 'radial-gradient(84% 102% at -5% -5%, rgba(184, 244, 96, 0.82) 0%, rgba(184, 244, 96, 0) 100%), radial-gradient(66% 84% at 104% 103%, rgba(255, 176, 110, 0.52) 0%, rgba(255, 176, 110, 0) 100%), radial-gradient(56% 66% at 50% 104%, rgba(255, 231, 87, 0.42) 0%, rgba(255, 231, 87, 0) 100%), linear-gradient(135deg, rgb(250, 250, 236) 0%, rgb(239, 249, 236) 100%)',

        'lime_tropical' => 'radial-gradient(84% 102% at -5% -5%, rgba(170, 243, 84, 0.86) 0%, rgba(170, 243, 84, 0) 100%), radial-gradient(68% 86% at 104% 103%, rgba(33, 200, 126, 0.66) 0%, rgba(33, 200, 126, 0) 100%), radial-gradient(58% 68% at 50% 104%, rgba(250, 221, 74, 0.42) 0%, rgba(250, 221, 74, 0) 100%), linear-gradient(135deg, rgb(246, 251, 236) 0%, rgb(234, 249, 238) 100%)',

        'bright_peach_leaf' => 'radial-gradient(78% 95% at -2% -2%, rgba(255, 208, 169, 0.64) 0%, rgba(255, 208, 169, 0) 100%), radial-gradient(62% 80% at 103% 102%, rgba(180, 228, 191, 0.46) 0%, rgba(180, 228, 191, 0) 100%), radial-gradient(54% 62% at 50% 102%, rgba(255, 235, 153, 0.34) 0%, rgba(255, 235, 153, 0) 100%), linear-gradient(135deg, rgb(252, 246, 238) 0%, rgb(248, 245, 236) 100%)',

        'bright_lilac_green' => 'radial-gradient(76% 96% at -3% -3%, rgba(219, 201, 243, 0.60) 0%, rgba(219, 201, 243, 0) 100%), radial-gradient(60% 78% at 103% 102%, rgba(170, 228, 196, 0.44) 0%, rgba(170, 228, 196, 0) 100%), radial-gradient(54% 62% at 48% 103%, rgba(237, 229, 170, 0.30) 0%, rgba(237, 229, 170, 0) 100%), linear-gradient(135deg, rgb(248, 244, 250) 0%, rgb(242, 247, 242) 100%)',

        'champagne_peach' => 'radial-gradient(78% 95% at -2% -2%, rgba(246, 223, 202, 0.44) 0%, rgba(246, 223, 202, 0) 100%), radial-gradient(62% 78% at 103% 102%, rgba(243, 229, 210, 0.34) 0%, rgba(243, 229, 210, 0) 100%), radial-gradient(52% 60% at 50% 103%, rgba(233, 214, 191, 0.22) 0%, rgba(233, 214, 191, 0) 100%), linear-gradient(135deg, rgb(251, 246, 239) 0%, rgb(247, 241, 235) 100%)',

        // 'mauve' => 'radial-gradient(78% 96% at -2% -3%, rgba(226, 218, 235, 0.48) 0%, rgba(226, 218, 235, 0) 100%), radial-gradient(60% 76% at 102% 103%, rgba(214, 204, 229, 0.34) 0%, rgba(214, 204, 229, 0) 100%), radial-gradient(52% 60% at 48% 102%, rgba(235, 226, 241, 0.24) 0%, rgba(235, 226, 241, 0) 100%), linear-gradient(135deg, rgb(246, 243, 248) 0%, rgb(241, 238, 244) 100%)',
    ],

    'images' => [
        'leafs' => get_template_directory_uri() . '/images/gift-card/covers/card_2.png',
        'dots'  => get_template_directory_uri() . '/images/gift-card/bg-dots.png',
        'waves' => get_template_directory_uri() . '/images/gift-card/bg-waves.png',
    ],

    'defaults' => [
        'gradient' => 'sky',
        'image'    => 'leafs',
    ],
];