# Improvement Suggestions

## Add runtime deprecation warnings to handlers scheduled for removal

- [ ] Add `trigger_error('...is deprecated...', E_USER_DEPRECATED)` to the constructors of FlowdockHandler, PHPConsoleHandler, and CubeHandler so users discover the deprecation before upgrading to Monolog 4.
- **Rationale:** These handlers are documented as deprecated but emit no runtime warnings, leaving users with a painful migration path on major-version upgrades.
- **Effort:** Small
- **Files:** `src/Monolog/Handler/FlowdockHandler.php`, `src/Monolog/Handler/PHPConsoleHandler.php`, `src/Monolog/Handler/CubeHandler.php`, `src/Monolog/Formatter/FlowdockFormatter.php`

## Enable strict test mode and coverage reporting

- [ ] Set `beStrictAboutTestsThatDoNotTestAnything="true"` in phpunit.xml.dist and configure coverage output (HTML/Clover) so coverage trends are visible.
- **Rationale:** The current configuration masks tests that don't actually assert anything and provides no coverage visibility, making it harder to catch test quality regressions in complex handlers.
- **Effort:** Small
- **Files:** `phpunit.xml.dist`, `.github/workflows/continuous-integration.yml`
