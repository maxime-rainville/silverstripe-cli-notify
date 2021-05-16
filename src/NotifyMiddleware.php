<?php
namespace MaximeRainville\SilverstripeCliNotify;

use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Middleware\HTTPMiddleware;

class NotifyMiddleware implements HTTPMiddleware
{
    public function process(HTTPRequest $request, callable $delegate)
    {
        if (Director::is_cli() && preg_match('/^dev\//i', $request->getURL()) && !$this->isUnitTest()) {
            try {
                $response = $delegate($request);
                $this->notify($request, $response);
                return $response;
            } catch (\Exception $ex) {
                $this->notifyError($request->getURL());
                throw $ex;
            }
        }

        return $delegate($request);
    }

    protected function notify(HTTPRequest $request, HTTPResponse $response): void
    {
        $notifier = NotifierFactory::create();
        $notification = new Notification();

        $url = $request->getURL();

        $notification->setTitle('Silverstripe CMS');
        if ($response->isError()) {
            $notification
                ->setBody("ERROR $url")
                ->setIcon(__DIR__.'/../icons/error.png');
        } else {
            $notification
                ->setBody("DONE $url")
                ->setIcon(__DIR__.'/../icons/success.png');
        }

        $notifier->send($notification);
    }

    protected function notifyError(string $url)
    {
        $notifier = NotifierFactory::create();
        $notification = new Notification();
        $notification
            ->setTitle('Silverstripe CMS')
            ->setBody("ERROR $url")
            ->setIcon(__DIR__.'/../icons/error.png');
        $notifier->send($notification);
    }

    /**
     * Attempts to guess if we are currently running in a Unit test context
     */
    protected function isUnitTest(): bool
    {
        if (!isset($_SERVER['SCRIPT_NAME'])) {
            return false;
        }

        $script = $_SERVER['SCRIPT_NAME'];

        return $script === './tests/cli-script.php';
    }

}
