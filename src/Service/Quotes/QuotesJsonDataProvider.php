<?php


namespace App\Service\Quotes;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheAdapterInterface;

final class QuotesJsonDataProvider extends AbstractDataProvider
{

    private string $quotesJsonFilePath;
    private array $data = [];
    private int $maxLimit;

    public function __construct(string $quotesJsonFilePath, int $maxLimit, ?CacheAdapterInterface $cache)
    {
        if (!\is_file($quotesJsonFilePath)) {
            throw new \RuntimeException("Unable to locate file: " . $quotesJsonFilePath);
        }

        $this->quotesJsonFilePath = $quotesJsonFilePath;
        $this->maxLimit = $maxLimit;

        parent::__construct($cache);
    }

    protected function internalFetch(string $key, int $limit): array
    {
        if ($limit > $this->maxLimit) {
            throw new \RuntimeException("Limit requested exceeds max limit");
        }

        $this->loadData();
        $count = 0;
        $result = [];

        foreach ($this->data as $obj) {
            if ($key === $obj["author"]) {
                $result[] = \strtoupper($obj["quote"]);

                if (++$count === $limit) {
                    break;
                }
            }
        }

        return $result;
    }

    private function loadData(): void {
        if (empty($this->data)) {
            $data = \json_decode(\file_get_contents($this->quotesJsonFilePath), true, 1024, JSON_THROW_ON_ERROR);
            $this->data = $data["quotes"];
        }
    }
}
