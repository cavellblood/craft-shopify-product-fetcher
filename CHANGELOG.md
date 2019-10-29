# shopify Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 1.0.6 - 2019-10-29
### Changed
- List all the products. API was limited to 250 results per page. Plugin now looks at the next page of results until all products are added to the dropdown.
- Only fetching `title` and `id` fields for a smaller network request.

## 1.0.5 - 2019-10-10
### Fixed
- GitHub-issue #7
### Added
- data-normalization to use selected values as an array

## 1.0.4 - 2019-10-07
### Fixed
- Fixed an issue with class-path for Craft3-Plugin class-name
- removed -RC1 flag of craftcms dependency
- use correct craft input-multiselect classes

## 1.0.0 - 2018-07-22
### Added
- Initial release
