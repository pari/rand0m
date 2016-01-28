class ProjectsUsers < ActiveRecord::Migration
  def self.up
       create_table :projects_users, :id => false, :force => true do |t|
           t.integer :user_id
           t.integer :project_id
           t.timestamps
       end
   end

   def self.down
       drop_table :projects_users
   end
end
