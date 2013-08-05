=== CP Easy Form Builder ===
Contributors: codepeople
Donate link: http://wordpress.dwbooster.com/forms/cp-easy-form-builder
Tags: contact form,contact form plugin,form builder,form to email,emailer,forms,form mailer,form creator,form maker,create form,build form
Requires at least: 3.0.5
Tested up to: 3.5
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

CP Easy Form Builder is a contact form plugin that allows creating contact forms and email them.

== Description ==

With CP Easy Form Builder you can:

    - Build a form
    - Use a visual form builder to create a form
    - Receive the form data via email
    - Add validation rules to the form
    - Add captcha anti-spam to the form
    - Style the form
    - Customize the emails

CP Easy Form Builder is a contact form plugin that allows creating **contact forms** and **email** them.

This forms plugin lets you to use its form builder to create contact forms, booking forms, or other types of forms that capture information via your website.

The form builder has a visual interface for creating the **contact form** with **field validation** and anti-spam **captcha** image verification included in all versions. The form builder is as simple as just drag and drop the form fields into the contact form.

= CP Easy Form Builder Features: =

* Allows to create contact forms **visually**, with a modern and simple interface
* **Sends** the contact form data to the email addresses that you provide
* Allows including additional user information (IP, user-agent)
* Allows to customize the text of **email messages**, including specific tags for each form field
* Includes **validation** of the contact form data: required fields, emails, dates, number, etc.
* Includes a **built-in captcha** image verification.
* Contact forms are processed using Ajax: more speed and comfort for the user

The contact form is rendered and validated using a modern jQuery script, compatible with mobile pages.

The kernel of the CP Easy Form Builder is its form maker (or form builder). It is 100% JavaScript and supports the basic email, text and comments fields. There are other versions that support more advanced fields. The form maker also allows to specify CSS classes for each form field (read more in the FAQ) or align various form fields in the same row. 

The validations, also integrated in the form builder, cover email form fields, confirmation form fields, length of the texts entered in the form fields, required form fields and other common form validation rules.

The captcha is built 100% into the plugin, there is no need for external captchas or anti-spam services. The captcha image can be visually configured to modify the font, colors, amount of noise and size. The captcha verification is made with Ajax to avoid reloading the page. The captcha configuration section is located below the form builder in the settings area.

== Installation ==

To install CP Easy Form Builder, follow these steps:

1.	Download and unzip the CP Easy Form Builder plugin
2.	Upload the entire cp-easy-form-builder/ directory to the /wp-content/plugins/ directory
3.	Activate the CP Easy Form Builder plugin through the Plugins menu in WordPress
4.	Configure the CP Easy Form Builder settings at the administration menu >> Settings >> CP Easy Form Builder
5.	To insert the CP Easy Form Builder into some content or post use the icon that will appear when editing contents

== Frequently Asked Questions ==

= Q: What means each field in the settings area? =

A: The CP Easy Form Builder product's page contains detailed information about each field and customization:

http://wordpress.dwbooster.com/forms/cp-easy-form-builder

= Q: How can I apply CSS styles to the form fields? =

A: Into the form editor (form builder), click a field to edit its details, there is a setting there named "Add CSS Layout Keywords". You can add the class name into that field, so the style specified into the CSS class will be applied to that field.

Note: Don't add style rules directly there but the name of a CSS class.

You can place the CSS class either into the CSS file of your template or into the file "cp-easy-form-builder\css\stylepublic.css" located into the plugin's folder.

= Q: Can I align the form in two or more columns?  =

A: Yes, use the "Add CSS Layout Keywords" field into the form creator for doing that. Into the form creator click a field and into its settings there is one field named "Add Css Layout Keywords". Into that field you can put the name of a CSS class that will be applied to the field.

There are some pre-defined CSS classes to use align two, three or four form fields into the same line. The CSS classes are named:

    column2
    column3
    column4
    
For example if you want to put two form fields into the same line then specify for both form fields the class name "column2".

= Q: Which is the CP Easy Form Builder shortcode for publishing the form? = 

This is the CP Easy Form Builder shortcode:

    CP_EASY_FORM_WILL_APPEAR_HERE
    
You can paste it in any place into a post/page or directly into the template using the do_shortcode function. In the edition of pages and posts there is a link that inserts the CP Easy Form Builder shortcode into the page/post.

== Other Notes ==

**If the form doesn't appear:** If the form doesn't appear in the public website that's probable due to a conflict with the theme. The solution in most cases is the following:

1. Edit the file cp_easy_form_builder.php, go to the line #22 where says:

    define('CP_EASYFORM_DEFAULT_DEFER_SCRIPTS_LOADING', false);
    
2. Put that configuration constant to true, example:

    define('CP_EASYFORM_DEFAULT_DEFER_SCRIPTS_LOADING', true);    

That way the scripts with be loaded in a different way that avoid conflicts with third party themes that force their own jQuery versions. This update may solve also conflicts with the form builder in the dashboard area.

**Other CP Easy Form Builder versions:** There is a pro version of the CP Easy Form Builder plugin that also supports these features:

* More form field types in the form builder: upload fields, phone fields, password fields, number fields, date fields, checkboxes, radio buttons, select drop-down fields
* Additional formatting options in the form builder: Section breaks, comment areas.
* Supports multiple forms in the website (max 1 form on each page)
* Automatic file uploads/attachments processing
* Supports tags for specific form fields into the email
* Includes autoreply
* WordPress Multi-site compatible

You can read more details about that version at http://wordpress.dwbooster.com/forms/cp-easy-form-builder


== Screenshots ==

1. Adding fields to the contact form using the form creator
2. Editing fields using the form builder
3. Contact form processing settings
4. Contact form validation settings
5. Inserting a contact form into a page
6. Built-in captcha image anti-spam protection

== Changelog ==

= 1.0 =
* First stable version released.
* More configuration options added.

== Upgrade Notice ==

= 1.0.1 =
First stable version released.