<?php

namespace App\Http\Controllers\Concerns;

trait SanitizesRichText
{
    private const ALLOWED_RICH_TEXT_TAGS = ['p', 'br', 'strong', 'em', 's', 'ul', 'ol', 'li', 'h2', 'h3', 'blockquote'];

    private function sanitizeRichText(?string $content): ?string
    {
        if ($content === null) {
            return null;
        }

        $stripped = strip_tags($content, self::ALLOWED_RICH_TEXT_TAGS);

        // strip_tags() removes disallowed tags but leaves attributes on tags
        // it keeps untouched — a raw POST bypassing the Tiptap client could
        // smuggle e.g. <p onclick="..."> through otherwise. None of the
        // allowed tags need attributes here, so collapse every remaining
        // opening tag to its bare form.
        $stripped = preg_replace('/<([a-z0-9]+)[^>]*>/i', '<$1>', $stripped);

        // Tiptap's "empty" document serializes to "<p></p>", not "". Normalize
        // any whitespace-only result to null so history/filter views and
        // "no note yet" placeholders keep behaving as before.
        $plainText = trim(strip_tags($stripped));

        return $plainText === '' ? null : $stripped;
    }
}
