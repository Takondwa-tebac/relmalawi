<?php

namespace Database\Seeders\Content;

use App\Models\Faq;
use App\Models\Page;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Page::query()->updateOrCreate(['slug' => 'faq'], [
            'eyebrow' => 'Questions and answers',
            'title' => 'Everything you',
            'title_accent' => 'want to know.',
            'description' => 'Plain answers for players, media partners and anyone curious about how REL raffles work, how winners are chosen and how prizes are paid.',
            'meta_title' => 'FAQ | REL Malawi',
            'meta_description' => 'Answers to common questions about playing REL raffles, mobile-money payouts, fair draws and becoming a media partner.',
        ]);

        foreach ($this->faqs() as $index => $faq) {
            // Keyed on the question so editors can change anything else without the seeder undoing it.
            Faq::query()->firstOrCreate(
                ['question' => $faq['question']],
                [
                    'category' => $faq['category'],
                    'answer' => $faq['answer'],
                    'show_on_home' => $faq['home'] ?? false,
                    'is_published' => true,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }

    /**
     * @return list<array{category: string, question: string, answer: string, home?: bool}>
     */
    private function faqs(): array
    {
        return [
            [
                'category' => 'Playing',
                'question' => 'How do I play?',
                'answer' => "Dial the USSD shortcode of the radio or TV station you listen to. Each station has its own shortcode. Choose the station, choose how many entries you want, confirm, and pay with your mobile-money PIN.\n\nThen listen to the station's draw shows. Winners are drawn live in the studio.",
                'home' => true,
            ],
            [
                'category' => 'Playing',
                'question' => 'How much is a ticket?',
                'answer' => 'One entry costs MWK 800. You can buy up to 10 entries in one USSD session. The confirmation screen shows what you are buying before you enter your PIN.',
                'home' => true,
            ],
            [
                'category' => 'Playing',
                'question' => 'Do more tickets give me a better chance?',
                'answer' => 'Yes. The more tickets you hold in a draw, the higher your chance of being selected.',
            ],
            [
                'category' => 'Playing',
                'question' => 'Do I need a smartphone?',
                'answer' => 'No. You play through USSD, which works on basic handsets as well as smartphones. You only need a phone with a mobile-money account.',
            ],
            [
                'category' => 'Playing',
                'question' => 'What happens if I win?',
                'answer' => "The presenter calls winners live on air. Your prize is credited to your mobile-money account straight away and you receive an SMS to confirm it.\n\nStations often interview winners live on air to hear their reaction.",
            ],
            [
                'category' => 'Payments & prizes',
                'question' => 'Which mobile-money networks can I use?',
                'answer' => 'You can pay with Airtel Money or TNM Mpamba.',
                'home' => true,
            ],
            [
                'category' => 'Payments & prizes',
                'question' => 'How do I get paid, and how fast?',
                'answer' => "Our system is connected to the mobile-money networks. As soon as the software selects a winner, the prize is paid into the winner's mobile-money account and an SMS follows. Payout is instant.",
                'home' => true,
            ],
            [
                'category' => 'Payments & prizes',
                'question' => 'Where do prizes come from?',
                'answer' => "Ticket payments are collected in the company's merchant account. Prizes are paid out of those funds to winners' mobile-money accounts.",
            ],
            [
                'category' => 'Fairness & regulation',
                'question' => 'How are winners chosen?',
                'answer' => "Winners are selected at random by REL's own software. A presenter starts the draw live in the studio and the computer picks the winning ticket.",
                'home' => true,
            ],
            [
                'category' => 'Fairness & regulation',
                'question' => 'Is the draw fair? Can the regulator check it?',
                'answer' => 'The software is built to randomise winner selection, which removes bias. The gaming regulator is given access to the system so it can test and vet the winner-selection algorithm. REL is regulated by the Malawi Gaming and Lotteries Authority (MAGLA).',
                'home' => true,
            ],
            [
                'category' => 'Fairness & regulation',
                'question' => 'Who can see the results?',
                'answer' => 'Partner stations and key stakeholders have dashboards with real-time reports, including a winners report, a transactions report and daily awards reports. The regulator also has access to the system.',
            ],
            [
                'category' => 'Media partners',
                'question' => 'How do stations earn?',
                'answer' => "A media house earns a commission calculated as a percentage of net revenue, which is revenue minus payouts, paid monthly. Presenters and middle management earn a commission on their own programme's sales, paid weekly.\n\nThe percentages are agreed with each media house.",
            ],
            [
                'category' => 'Media partners',
                'question' => 'What must a media partner do?',
                'answer' => "A partner promotes the raffle on its channels at an agreed frequency, using jingles and hype scripts produced in-house. It runs draws live in the studio on at least four live shows a day and calls the winners on air.\n\nIt also checks how well the promotion is reaching its audience and keeps a sense of ownership of the game among its on-air team.",
            ],
            [
                'category' => 'Media partners',
                'question' => 'What does REL provide?',
                'answer' => "REL builds and runs the technology platform, keeps the operating licence renewed, trains the station's staff and supports them end to end. Each partner gets a dedicated relationship manager.\n\nREL also covers USSD design and set-up, IT support and customer care, and SMS and mobile-money transaction fees.",
            ],
            [
                'category' => 'Media partners',
                'question' => 'How do I become a partner?',
                'answer' => 'Send us a message on the contact page and we will get back to you to talk it through. Partners have creative freedom to brand the raffle and write their own campaign name, teasers and adverts.',
            ],
        ];
    }
}
