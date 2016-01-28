# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#   
#   cities = City.create([{ :name => 'Chicago' }, { :name => 'Copenhagen' }])
#   Major.create(:name => 'Daley', :city => cities.first)

Location.create(:name => 'Ameerpet ShowRoom' , :state => 'AndhraPradesh')
Location.create(:name => 'Ameerpet Bigbazaar' , :state => 'AndhraPradesh')
Location.create(:name => 'Kukatpally ShowRoom' , :state => 'AndhraPradesh')
Location.create(:name => 'Chickpet ShowRoom' , :state => 'Karnataka')
Location.create(:name => 'Jayanagar ShowRoom' , :state => 'Karnataka')


Division.create(:name => 'LADIES')
Division.create(:name => 'MENS')
Division.create(:name => 'KIDS')

first_division = Division.find(:first)
first_division.sections.create(:name => 'PATTU')
first_division.sections.create(:name => 'HANDLOOM')


first_section = Section.find(:first)
first_section.departments.create(:name => 'ARNI')
first_section.departments.create(:name => 'KANCHI')

Employee.create( :username => 'chandu' , :fullname => 'Chandu Nannapaneni' , :email => 'paripurnachand@gmail.com' , :empid => '1044', :password => 'something' , :password_confirmation =>'something', :location_id =>2 , :department_id =>2 )

# 
# second_section = Section.find(:all)[1]
# second_section.departments.create(:name => 'ARNI')
# second_section.departments.create(:name => 'KANCHI')


