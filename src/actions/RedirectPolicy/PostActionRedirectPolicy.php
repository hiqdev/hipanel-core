<?php

declare(strict_types=1);

namespace hipanel\actions\RedirectPolicy;

/**
 * Defines a strategy for deciding where the application should redirect
 * after an action has been successfully executed.
 *
 * Implementations encapsulate business rules that express a preferred
 * redirect target (for example: view page, previous page, or search page),
 * without performing the redirect itself.
 *
 * This interface allows redirect behavior to be configured and replaced
 * without modifying the action or resolver logic.
 */
interface PostActionRedirectPolicy
{
    /**
     * Determines the preferred redirect target for the given action.
     *
     * The returned target represents intent only; the actual redirect
     * may fall back to another target if the preferred one is not feasible
     * (for example, when a view page cannot be shown).
     */
    public function preferredTarget(): RedirectTarget;
}
