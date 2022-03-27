<?php
/*
 * Copyright 2022 Michael Lucas <nasumilu@gmail.com>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Nasumilu\CGS4183\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Image
{

    #[ORM\Column(name: 'path', type: 'string', length: 128)]
    private ?string $path;

    #[ORM\Column(name: 'mime_type', length: 16, enumType: MimeType::class)]
    private ?MimeType $mimeType;

    public function __construct(
        ?string              $path = null,
        MimeType|string|null $mimeType = null)
    {
        if(is_string($mimeType)) {
            $mimeType = MimeType::from($mimeType);
        }
        $this->path = $path;
        $this->mimeType = $mimeType;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     * @return Image
     */
    public function setPath(?string $path): Image
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return MimeType|null
     */
    public function getMimeType(): MimeType|null
    {
        return $this->mimeType;
    }

    /**
     * @param MimeType|string|null $mimeType
     * @return Image
     */
    public function setMimeType(MimeType|string|null $mimeType): Image
    {
        if(is_string($mimeType)) {
            $mimeType = MimeType::from($mimeType);
        }
        $this->mimeType = $mimeType;
        return $this;
    }



}