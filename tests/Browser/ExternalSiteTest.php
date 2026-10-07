<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExternalSiteTest extends DuskTestCase
{
    public function test_carevision_scan()
    {
        $this->assertFileExists(base_path('tests/video/face.y4m'), 'ملف الفيديو مش موجود');

        $this->browse(function (Browser $browser) {
            $next = '.sheet-nav .btn-primary';
            $t = 700;

            $browser->visit('https://poc.carevision.app/app/welcome')
                ->waitForText('Get Started', 20)
                ->pause($t)
                ->press('Get Started')

                ->waitFor('.disclaimer-card', 10)
                ->pause($t)
                ->click('.disclaimer-checkbox .checkbox-label')
                ->pause(300)
                ->press('Continue')

                ->waitFor('.form-input', 10)
                ->pause($t)
                ->type('.form-input', 'Test User')
                ->type('.phone-input', '791234567')
                ->click('label[for=consent-chk]')
                ->click('label[for=agree-chk]')
                ->pause(300)
                ->click($next)

                ->waitFor('.age-number-input', 10)
                ->pause($t)
                ->type('.age-number-input', '30')
                ->click('.gender-card')
                ->pause(300)
                ->click($next)

                ->waitFor('.measure-input', 10)
                ->pause($t)
                ->type('.measure-card:nth-child(1) .measure-input', '175')
                ->type('.measure-card:nth-child(2) .measure-input', '75')
                ->pause(300)
                ->click($next);

            for ($i = 0; $i < 5; $i++) {
                $browser->waitFor('.oi-q-option', 10)
                    ->pause($t)
                    ->click('.oi-q-option:nth-child(2)')
                    ->pause($t);
            }

            $browser->waitFor('.template-card', 15)
                ->pause($t)
                ->click($next)
                ->waitFor('.chat-list', 10)
                ->waitFor('.sheet-nav .btn-green', 15)
                 ->waitUntilEnabled('.sheet-nav .btn-green', 60)
                ->pause(500)
                ->screenshot('before-start-scan')
                ->click('.sheet-nav .btn-green');

            $browser->pause(3000)->screenshot('after-click');
            fwrite(STDERR, "\nAFTER CLICK: " . $browser->driver->getCurrentURL() . "\n");

            $browser->pause(10000)->screenshot('scan-10s')
                ->pause(20000)->screenshot('scan-30s')
                ->pause(15000)->screenshot('scan-result');
        });
    }
}
