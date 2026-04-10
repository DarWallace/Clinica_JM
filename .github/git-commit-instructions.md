Write a sharp and concise commit message in the conventional form of commits. Do not write more than two sentences. I will provide you with the output of the 'git diff -staged' command, and you must convert it into the commit message. Focus particularly on the use of the GitMoji convention. Use the present tense for the commit message. The lines must not exceed 120 characters. Use {locale} as the language to respond.

Here are **some** examples of emoji usage (there are more in the GitMoji convention):

| Emoji |       Emoji Name        |   Type   | Category                | Use case                                    |
|:-----:|:-----------------------:|:--------:|:------------------------|:--------------------------------------------|
|   ✨   |       :sparkles:        |   feat   | Creating features       | Development of a new feature / New feature  |
|     |       :bookmark:        |  chore   | Managing delivery       | Release / Version tags.                     |
|     |     :construction:      |   feat   | Creating features       | Work in progress / Code progress            |
|     |        :bricks:         |   feat   |                         | Changes in infrastructure                   |
|     |    :european_castle:    |   feat   | Managing infrastructure | Adding changes to the launch plan           |
|     |        :trident:        |   feat   | Managing infrastructure | Add or remove permissions in infrastructure |
|  ️  | :building_construction: | refactor | Creating changes        | Making architectural changes                |
|     |       :seedling:        |   feat   |                         | Add seeds                                   |
|     |      :green_heart:      |  chore   | Managing integration    | Continuous Integration / CI                 |
|     |  :construction_worker:  |  chore   | Managing integration    | New continuous integration build            |
|  ⬇️   |      :arrow_down:       | refactor | Creating reliability    | Downgrade dependencies                      |
|  ⬆️   |       :arrow_up:        | refactor | Creating reliability    | Upgrade dependencies                        |
|     |        :pushpin:        |   feat   | Creating reliability    | Pin dependency to a specific version        |
|   ➕   |    :heavy_plus_sign:    | refactor | Creating reliability    | New dependency(ies)                         |
|   ➖   |   :heavy_minus_sign:    | refactor | Creating reliability    | Remove dependency(ies)                      |
|  ️  |        :package:        |  chore   | Refining quality        | New packages                                |
|  ♻️   |        :recycle:        | refactor | Refining quality        | Code refactoring                            |
|     |          :art:          |  style   | Refining quality        | Improve structure and format of the code    |
|     |         :truck:         | refactor | Creating changes        | Move or rename files                        |
|     |         :bento:         |   feat   | Creating changes        | Add assets                                  |
|     |         :fire:          | refactor | Creating changes        | Delete code or files                        |
|     |    :rotating_light:     |  style   | Refining defects        | Fix compiler or linter warnings             |
|  ✏️   |        :pencil2:        |   fix    | Refining defects        | Fix typos                                   |
|  ⚰️   |        :coffin:         |  chore   |                         | Clean code / remove dead code               |
|     |         :whale:         |  chore   | Refining platform       | Related to docker                           |
|     |     :card_file_box:     |          |                         | Related to databases                        |
|     |          :bug:          |   fix    | Refining defects        | Bug fixes                                   |
|  ️  |       :ambulance:       |   fix    | Refining stability      | Critical Hot-Fix                            |
|     |         :boom:          |   feat   | Creating features       | Breaking changes / Critical changes         |
|     |   :adhesive_bandage:    |          |                         | Non-critical fix                            |
|     |      :see_no_evil:      |   feat   | Managing infrastructure | gitignore                                   |
|   ✋   |         :hand:          |   feat   |                         | Explore alternative implementation          |
|     |         :mute:          |   docs   | Refining defects        | Remove prints or logs                       |
|     |      :loud_sound:       |   docs   | Refining defects        | Include logs or prints                      |
|     |    :speech_balloon:     |   feat   | Managing value          | Update literals, text, and "magic numbers"  |
|     |         :memo:          |   docs   | Managing delivery       | Documentation                               |
|  ✏️   |        :pencil:         |   docs   | Managing delivery       | Documentation                               |
|     |    :page_facing_up:     |  chore   | Managing delivery       | Changes to the build process.               |
|     |      :safety_vest:      |          |                         | Model and database validations              |
|     |      :stethoscope:      |          |                         | Add Healthcheck                             |
|     |       :test_tube:       |          |                         | Add tests conditioned to fail               |
|   ✅   |   :white_check_mark:    |   test   | Refining defects        | Add tests conditioned to succeed            |
|  ⚗️   |        :alembic:        |   feat   | Creating ideas          | Experiments                                 |
|  ⚡️   |          :zap:          |   feat   | Refining quality        | Performance improvement                     |
|     |        :rocket:         |  chore   | Managing delivery       | Deployment                                  |
|     |       :lipstick:        |   feat   | Creating changes        | Related to UI                               |
|     |   :children_crossing:   |   feat   | Managing value          | Related to UX                               |
|     | :globe_with_meridians:  |   feat   | Managing value          | Internationalization and localization       |
|     |        :iphone:         | refactor | Creating changes        | Responsive design / for mobile devices      |
|     |          :cop:          |  chore   | Managing integration    | Add things related to security              |
|  ️  |         :lock:          |   fix    | Refining stability      | Security issues                             |
|     | :closed_lock_with_key:  |          |                         | Secrets and keys                            |
|     |    :deciduous_tree:     |          |                         | .env files / environment variables          |
|     |        :wrench:         |   feat   | Creating reliability    | Modify configuration files                  |
|     |         :poop:          | refactor | Creating ideas          | Poorly written code / FIXME                 |
|     |         :beers:         |   feat   | Creating ideas          | Writing code while intoxicated              |
|     |          :egg:          | refactor | Creating changes        | Add an Easter Egg                           |

**NEVER use emojis as text; e.g., ":sparkles:", but ALWAYS use their graphic version; e.g., "✨"**

Here is the structure I expect:
[EMOJI] [TYPE]([MODIFIED FILE OR TOPIC]): [CONCISE AND SHARP DESCRIPTION OF THE CHANGES WRITTEN IN "{locale}" AS LANGUAGE]

Here are some examples of what I expect in spanish as commit messages:

1. ✨ feat(workers): Update environment variables.
2. ⚡️ feat(components/Hash): Performance improvement in hashes.
3.  chore(Dockerfile): Dockerize the project.
4.  feat(seeds/users.js): Add seeders for the User entity.
5. ♻️ refactor(App/index.js): Change CamelCase variables to snake_case.
6.  style(App): Correct eslint errors.
7.  feat(models/index.js): Progress in the development of models.
8.  chore(auth0): Connect the project with auth0 for login.
9.  refactor(config.js): Move the config.js file to config/index.js.
10.  env(template.env): Make a .env file as a guide without secrets.
11. ⚗ feat(experiments/LoginFlow): Try new login method with social media.
12. ✅ test(tests/utils.test.js): Add success test cases for utility functions.
13. ✏️ docs(README.md): Update project instructions and setup guide.
14. ➕ refactor(dependencies): Include new package for data validation.
15. ♻️ refactor(client/Auth): Simplify authentication flow and reduce redundancy.
16. ⬇️ refactor(package.json): Downgrade react version due to compatibility issues.
17. ✋ feat(exploratory/AuthFlow): Test alternative approach for user session management.
18. ➖ refactor(dependencies): Remove unused moment.js library.
19. ✏ fix(typo): Correct typo in the README header.
20. ⚗ feat(experiments/SortingAlgorithm): Evaluate performance of new sorting method.
21.  feat(UI/animations): Add interactive button hover effects.
22. ⚰️ chore(dead-code): Remove unused helper functions from utils.
23. ⚡️ feat(server/cache): Improve caching mechanism to reduce load times.
24. ✅ test(tests/OrderService.test.js): Add test coverage for order processing.
25. ✏️ docs(CONTRIBUTING.md): Revise contribution guidelines for new members.
26. ⬆️ refactor(dependencies): Upgrade express to the latest version.
27. ⚡️ feat(API/RateLimit): Implement rate limiting to enhance performance.
28. ➕ refactor(config): Add new environment variable for feature toggle.
29. ✋ feat(Auth/LoginMethods): Explore social login alternatives for new users.
30. ⚗ feat(R&D/FeatureFlag): Experiment with feature flags for A/B testing.
31. ✨ feat(notifications): Add support for in-app notifications.
32. ⚰️ chore(cleanup): Delete obsolete configuration files.
33. ✅ test(tests/Cache.test.js): Include tests to verify cache invalidation.
34. ⬇️ refactor(dependencies): Downgrade node-fetch due to security concerns.
35. ✏ fix(docs): Correct spelling errors in API documentation.
36. ⬆️ refactor(dev-dependencies): Update eslint and related plugins.
37. ♻️ refactor(client/components): Modularize reusable components for consistency.
38. ➖ refactor(package.json): Remove deprecated npm scripts.
39. ✨ feat(UI/ThemeSwitcher): Introduce dark mode toggle for the application.
40. ⚡️ feat(database/queries): Optimize a complex query to reduce load time.

Below is the output of 'git diff --staged':

---

{diff}

=====

Y la respuesta como ejemplo seria esta:

✨ feat(ResultadosCuestionariosController.php): Mejora en el manejo de respuestas válidas en cuestionarios.  
♻️ refactor(ResultadosCuestionariosController.php): Simplifica la lógica de cálculo de corrección de respuestas.
