class CreateProjects < ActiveRecord::Migration
  def self.up
    create_table :projects do |t|
      t.string :name
      t.string :description
      t.timestamps
#      t.references :user
    end
    
    
  end

  def self.down
    drop_table :projects
  end
end
