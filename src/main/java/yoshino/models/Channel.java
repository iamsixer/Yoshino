package yoshino.models;

import org.hibernate.annotations.ColumnDefault;

import javax.persistence.*;
import java.util.Date;

/**
 * Created by Volio on 2017/1/7.
 */
@Entity
@Table(name = "channels")
public class Channel {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne
    @JoinColumn(name = "uid", nullable = false)
    private User user;

    @Column(name = "stream_key", nullable = false, length = 100, unique = true)
    private String streamKey;

    @Column(name = "name", nullable = false, length = 200)
    private String name;

    @Column(name = "streaming", nullable = false)
    private boolean streaming;

    @Column(name = "created_at", nullable = false)
    @Temporal(value = TemporalType.TIMESTAMP)
    @ColumnDefault("CURRENT_TIMESTAMP")
    private Date createdAt;

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public User getUser() {
        return user;
    }

    public void setUser(User user) {
        this.user = user;
    }

    public String getStreamKey() {
        return streamKey;
    }

    public void setStreamKey(String streamKey) {
        this.streamKey = streamKey;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public boolean isStreaming() {
        return streaming;
    }

    public void setStreaming(boolean streaming) {
        this.streaming = streaming;
    }

    public Date getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(Date createdAt) {
        this.createdAt = createdAt;
    }
}
