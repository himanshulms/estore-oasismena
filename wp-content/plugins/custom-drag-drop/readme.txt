2.  Create custom fields using CFS (Custom field) 

3. Displayed Dynamic content in page template  

 # Get Field group order 

[
    $order = get_post_meta(get_the_ID(), '_cfs_field_order', true); 
    $ordered_groups = explode(',', $order); // return Array
]

 # $ordered_groups has contained a name of Field group as a Array form
 # Get order group name in main section using loop 

   <?php if($group_id == 'Services Section')?> 
 
 # And put sections in IF condition using Field Group name as you defined backend 
   like About section, Hero Banner Section, Services Section. 

 # Defined Fixed class for sections by indexing 

    <section class="inner_section_<?= $i; ?>"> 

   * Inner_section_1 for section One  

   * Inner_section_2 for section Two 
 
 4. Column moved on Left and right 

   # Use a field of True/false on CFS Field creating 
   # There is used a Column Reverse field  
   # When we checked on this, the flex-row-reverse CSS was applied to that section 

   # Code :  In below code, column_reverse is a field Slug 

   <div class="row <?php echo CFS()->get('column_reverse') ? 'flex-row-reverse' : '';?>"> 