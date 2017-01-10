# About #

This is _ninja-forms-session_. A [Wordpress] plugin and [Ninja Forms][ninja-forms] add-on which 
allows you to save form data to the current session and then use it elsewhere.
 
## Usage ##

### Merge Tags ###

* Navigate to editing a Form 1
  * Go to **Emails & Actions** and add "Save to Session"
  * Note the the _FIELD KEY_ under **Administration** of a field (e.g. first_name)
* Navigate to editing a Form 2
  * Go to **Form Fields** and configure a field
  * Under **DEFAULT VALUE** use the MergeTag _{session:FIELD_KEY}_ (e.g. {session:first_name})

### Short Codes ###

* Navigate to editing a Form 1
  * Go to **Emails & Actions** and add "Save to Session"
  * Note the the _FIELD KEY_ under **Administration** of a field (e.g. first_name)
* Navigate to editing a Page
  * Add ShortCode _[nf_session_field_value field_key=FIELD_KEY]_ (e.g. [nf_session_field_value field_key=first_name])
  
### Advanced ###

* Field values are stores in _session:FIELD_KEY_
* Field's Calc Values are stored in _session:FIELD_KEY:calc_
* Choice Field's Labels are stored in _session:FIELD_KEY:label_

### Session Expiration ###

By default sessions expire in **30 minutes** due _wp_session_expiration_

## License ##

    ninja-forms-session is licensed under GPLv3.

    ninja-forms-session is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    ninja-forms-session is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with ninja-forms-session.  If not, see <http://www.gnu.org/licenses/>.

[ninja-forms]: https://ninjaforms.com/
[wordpress]: https://wordpress.com/
