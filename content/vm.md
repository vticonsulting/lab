---
title: Models
list:
  - account
  - activity
  - adjective
  - asset
  - referrer
  - advocate
  - application
  - system
  - recipe
  - ingredient
  - channel
  - affiliate
  - affirmation
  - album
  - alert
  - avail
  - book
  - campaign
  - case
  - color
  - contact
  - control_panels
  - country
  - daypart
  - donor
  - event
  - organization_administrators
  - note
  - event_type
  - faq
  - flavor
  - interest
  - invoice
  - job
  - keyword
  - latitude
  - longitude
  - library
  - link
  - market
  - make
  - model
  - feature
  - message
  - module
  - movie
  - name
  - notice
  - noun
  - order
  - organization
  - organization_type
  - package
  - person
  - play
  - pledge
  - plugin
  - phone
  - post
  - post_type
  - preposition
  - price_guide
  - product
  - program
  - prototype
  - quote
  - rating
  - region
  - report
  - review
  - permission_role
  - score
  - school
  - county
  - timezone
  - user_activity
  - session
  - service
  - site
  - sponsor
  - song
  - staff
  - status
  - student
  - task
  - teacher
  - team
  - testimonial
  - theme
  - todo
  - topic
  - user
  - user_group
  - user_profile
  - user_type
  - user_tasks
  - user_task_list_tasks
  - user_task_lists
  - user_task_templates
  - user_notes
  - video_category
  - video
  - verb
  - volunteer
  - widget
methods:
  - index
  - show
  - create
  - edit
  - update
  - store
  - destroy
attributes:
  - abbreviation
  - address
  - address_2
  - alias
  - amount
  - guard_name
  - avatar_url
  - due_date
  - completed_at
  - completed_by_user_id
  - created_by_user_id
  - body
  - cancelled
  - cents
  - city
  - content
  - country
  - created_at
  - current_step
  - deleted
  - deleted_at
  - description
  - display_name
  - finished
  - first_name
  - goal
  - item
  - ip_address
  - label
  - last_name
  - lat
  - long
  - name
  - path
  - photo_url
  - poster_url
  - published_at
  - read
  - source
  - state
  - status
  - submitted_at
  - total_steps
  - type
  - updated_at
  - url
  - value
  - video_url
  - zip
  - user_agent
  - is_searchable
  - image_name
  - can_delete
  - gender
  - dob
  - display_order
  - registered
  - can_edit
  - has_email
  - url
  - hash
  - original_url
  - override
  - slug
  - read_at
  - starts_at
  - ends_at
  - archived
---

```php
private function _fetch_table()
private function _return_type($multi = false)
private function _run_validation($data)
private function _set_database()
private function _set_where($params)
public function as_array()
public function as_object()
public function count_all()
public function count_by()
public function count_rows()
public function created_at($row)
public function delete_by()
public function delete_many($primary_values)
public function delete($id)
public function dropdown()
public function get_all()
public function get_by()
public function get_many_by()
public function get_many($values)
public function get_next_id()
public function get_page( $limit, $offset )
public function get_skip_validation()
public function get($primary_value)
public function insert_many($data, $skip_validation = false)
public function insert($data, $skip_validation = false)
public function limit($limit, $offset = 0)
public function order_by($criteria, $order = 'ASC')
public function relate($row)
public function serialize($row)
public function skip_validation()
public function table()
public function trigger($event, $data = false)
public function unserialize($row)
public function update_all($data)
public function update_by()
public function update_many($primary_values, $data, $skip_validation = false)
public function update($primary_value, $data, $skip_validation = false)
public function updated_at($row)
public function with_deleted()
public function with($relationship)
```
