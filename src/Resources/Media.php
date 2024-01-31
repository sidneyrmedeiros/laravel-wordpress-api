<?php

namespace RickWest\WordPress\Resources;

use RickWest\WordPress\Resources\Traits\HasAuthor;
use RickWest\WordPress\Resources\Traits\HasDate;
use RickWest\WordPress\Resources\Traits\HasSlug;

class Media extends Resource
{
    use HasSlug;
    use HasDate;
    use HasAuthor;

    /**
     * Attach a file to the request.
     *
     * @param  string|array  $name
     * @param  string|resource  $contents
     * @param  string|null  $filename
     * @param  array  $headers
     * @return $this
     */
    public function attach($name, $contents = '', $filename = null, array $headers = []): static
    {
        $this->client->attach($name, $contents, $filename, $headers);

        return $this;
    }
}
